<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\MidtransService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index(){
        $keranjang = session()->get('keranjang', []);
        if (empty($keranjang)){
            return redirect()->route('keranjang.index')->with('error', 'Keranjang masih kosong');
        }
        $pengiriman = session()->get('pengiriman');
        if (!$pengiriman){
            return redirect()->route('keranjang.index')->with('error', 'Silakan pilih metode pengiriman terlebih dahulu');
        }
        $produkIds = array_keys($keranjang);
        $produk = Produk::with('promoAktif')->whereIn('id', $produkIds)->get()->keyBy('id');
        $subtotal = 0;
        $totalDiskon = 0;
        foreach ($keranjang as $produkId => $item){
            $dataProduk = $produk->get($produkId);
            if (!$dataProduk){
                continue;
            }
            $hargaAsli = (float) $dataProduk->harga;
            $hargaSetelahDiskon = $hargaAsli;
            if ($dataProduk->promoAktif){
                $promo = $dataProduk->promoAktif;
                if ($promo->tipe_potongan === 'persentase'){
                    $hargaSetelahDiskon = $hargaAsli - ($hargaAsli * $promo->besar_potongan / 100);
                } elseif ($promo->tipe_potongan === 'nominal'){
                    $hargaSetelahDiskon = $hargaAsli - $promo->besar_potongan;
                }
                $hargaSetelahDiskon = max(0, $hargaSetelahDiskon);
            }
            $jumlah = (int)($item['jumlah'] ?? 0);
            $subtotal += $hargaAsli * $jumlah;
            $totalDiskon += ($hargaAsli - $hargaSetelahDiskon) * $jumlah;
        }
        $ongkir = (int) ($pengiriman['ongkir'] ?? 0);
        $totalBayar = $subtotal - $totalDiskon + $ongkir;
        $alamat = auth()->user()->alamatPengiriman()->where('is_utama', true)->first();
        if (!$alamat){
            return redirect()->route('alamat.create')->with('error', 'Silakan tambahkan alamat pengiriman terlebih dahulu');
        }
        return view('frontend.checkout.index', compact(
            'keranjang',
            'produk',
            'alamat',
            'pengiriman',
            'subtotal',
            'totalDiskon',
            'ongkir',
            'totalBayar'
        ));
    }
    public function proses(Request $request, MidtransService $midtrans){
        $request->validate([
            'metode' => 'required|in:bank,ewallet',
            'bank' => 'nullable|required_if:metode,bank|in:BRI,BNI,BCA,Mandiri',
            'ewallet' => 'nullable|required_if:metode,ewallet|in:GoPay',
        ]);
        $keranjang = session()->get('keranjang', []);
        $pengiriman = session()->get('pengiriman');
        if (empty($keranjang)){
            return response()->json([
                'pesan' => 'Keranjang masih kosong'
            ], 422);
        }
        if (!$pengiriman){
            return response()->json([
                'pesan' => 'Metode pengiriman belum dipilih'
            ], 422);
        }
        $produkIds = array_keys($keranjang);
        $produk = Produk::with('promoAktif')->whereIn('id', $produkIds)->get()->keyBy('id');
        $totalHarga = 0;
        foreach ($keranjang as $produkId => $item){
            $dataProduk = $produk->get($produkId);
            if (!$dataProduk){continue;}
            $harga = $dataProduk->harga;
            if ($dataProduk->promoAktif){   
                $promo = $dataProduk->promoAktif;
                if ($promo->tipe_potongan === 'persentase'){
                    $harga = $harga - ($harga * $promo->besar_potongan / 100);
                } elseif ($promo->tipe_potongan === 'nominal'){
                    $harga = $harga - $promo->besar_potongan;
                }
            }
            $jumlah = (int) ($item['jumlah'] ?? 0);
            $totalHarga += $harga * $jumlah;
        }
        $ongkir = (int) ($pengiriman['ongkir'] ?? 0);
        $totalBayar = $totalHarga + $ongkir;
        $alamat = auth()->user()->alamatPengiriman()->where('is_utama', true)->first();
        if (!$alamat){
            return response()->json([
                'pesan' => 'Alamat pengiriman belum tersedia'
            ], 422);
        }
        $kodePesanan = 'PNK-' . now()->format('YmdHis') . '-' . strtoupper(\Illuminate\Support\Str::random(5));
        try{
            $hasil = DB::transaction(function()use(
                $request,
                $keranjang,
                $produk,
                $alamat,
                $pengiriman,
                $totalHarga,
                $ongkir,
                $totalBayar,
                $kodePesanan,
                $midtrans
            ){
            $order = Order::create([
                'user_id' => auth()->id(),
                'alamat_id' => $alamat->id,
                'kode_pesanan' => $kodePesanan,
                'total_harga' => $totalHarga,
                'ongkir' => $ongkir,
                'total_bayar' => $totalBayar,
                'payment_type' => $request->metode,
                'payment_method' => match ($request->metode){
                    'qris' => 'QRIS',
                    'bank' => $request->bank,
                    'ewallet' => $request->ewallet,
                    default => throw new \InvalidArgumentException('Metode pembayaran tidak tersedia'),
                },
                'status_pembayaran' => 'pending',
                'status_pesanan' => 'pending',
                'tanggal_pengiriman' => $pengiriman['tanggal'] ?? now()->toDateString(),
                'delivery_slot' => null,
                'jam_pengiriman' => $pengiriman['waktu'] ?? now()->format('H:i:s'),
                'catatan' => '',
            ]);
            foreach ($keranjang as $produkId => $item){
                $dataProduk = $produk->get($produkId);
                if (!$dataProduk){
                    continue;
                }
                $hargaAsli = $dataProduk->harga;
                $hargaSetelahDiskon = $hargaAsli;
                $tipePotongan = null;
                $besarPotongan = 0;
                if ($dataProduk->promoAktif){
                    $promo = $dataProduk->promoAktif;
                    $tipePotongan = $promo->tipe_potongan;
                    $besarPotongan = $promo->besar_potongan;
                    if ($promo->tipe_potongan === 'persentase'){
                        $hargaSetelahDiskon = $hargaAsli - ($hargaAsli * $promo->besar_potongan / 100);
                    } elseif ($promo->tipe_potongan === 'nominal'){
                        $hargaSetelahDiskon = $hargaAsli - $promo->besar_potongan;
                    }
                }
                $qty = (int) ($item['jumlah'] ?? 0);
                OrderItem::create([
                    'order_id' => $order->id,
                    'produk_id' => $dataProduk->id,
                    'harga_asli' => $hargaAsli,
                    'tipe_potongan' => $tipePotongan,
                    'besar_potongan' => $besarPotongan,
                    'harga_setelah_diskon' => $hargaSetelahDiskon,
                    'qty' => $qty,
                    'subtotal' => $hargaSetelahDiskon * $qty,
                ]);
            }
            $snapToken = $midtrans->buatPembayaran([
                'transaction_details' => [
                    'order_id' => $kodePesanan,
                    'gross_amount' => $totalBayar,
                ],
                'enabled_payments' => match ($request->metode){
                    'bank' => match ($request->bank){
                        'BCA' => ['bca_va'],
                        'BNI' => ['bni_va'],
                        'BRI' => ['bri_va'],
                        'Mandiri' => ['echannel'],
                        default => throw new \InvalidArgumentException('Bank pembayaran tidak tersedia'),
                    },
                    'ewallet' => match ($request->ewallet){
                        'GoPay' => ['gopay'],
                        default => throw new \InvalidArgumentException('E-wallet pembayaran tidak tersedia'),
                    },
                    default => throw new \InvalidArgumentException('Metode pembayaran tidak tersedia'),
                },
            ]);
            return [
                'order' => $order,
                'snap_token' => $snapToken,
            ];
        });
        return response()->json([
            'pesan' => 'Pembayaran berhasil dibuat',
            'kode_pesanan' => $hasil['order']->kode_pesanan,
            'snap_token' => $hasil['snap_token'],
        ]);
        }catch(\Throwable $e){
            Log::error('Gagal membuat pembayaran Midtrans',[
                'metode' => $request->metode,
                'bank' => $request->bank,
                'ewallet' => $request->ewallet,
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'pesan' => 'Gagal melakukan pembayaran',
            ], 500);
        }
    }
}
