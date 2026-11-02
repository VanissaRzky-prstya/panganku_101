<?php

namespace App\Http\Controllers;
use App\Models\Produk;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    public function index(){
        $keranjang = session()->get('keranjang', []);
        $produkIds = array_keys($keranjang);
        $produk = Produk::with('promoAktif')->whereIn('id', $produkIds)->get()->keyBy('id');
        $alamat = auth()->user()->alamatPengiriman()->where('is_utama', true)->first();
        return view('frontend.keranjang.index', compact(
            'keranjang',
            'produk',
            'alamat'
        ));
    }

    public function tambah(Request $request){
        $request->validate([
            'produk_id' => ['required', 'integer', 'exists:produks,id'],
        ]);
        $produk = Produk::findOrFail($request->produk_id);
        if ($produk->stok < 1){
            return response()->json([
                'success' => false,
                'message' => 'Produk sedang habis.'
            ], 422);
        }
        $keranjang = session()->get('keranjang', []);
        if (isset($keranjang[$produk->id])){
            $jumlahBaru = $keranjang[$produk->id]['jumlah'] + 1;
            if ($jumlahBaru > $produk->stok){
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah produk melebihi stok yang tersedia.'
                ], 422);
            }
            $keranjang[$produk->id]['jumlah'] = $jumlahBaru;
        }else{
            $keranjang[$produk->id] = [
                'produk_id' => $produk->id,
                'jumlah' => 1,
            ];
        }
        session()->put('keranjang', $keranjang);
        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang.',
            'jumlah_keranjang' => collect($keranjang)->sum('jumlah'),
        ]);
    }

    public function lebih(Request $request){
        $request->validate([
            'produk_id' => ['required', 'integer', 'exists:produks,id'],
        ]);
        $produk = Produk::findOrFail($request->produk_id);
        $keranjang = session()->get('keranjang', []);
        if (!isset($keranjang[$produk->id])){
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan di keranjang.'
            ], 404);
        }
        $jumlahBaru = $keranjang[$produk->id]['jumlah'] + 1;
        if ($jumlahBaru > $produk->stok){
            return response()->json([
                'success' => false,
                'message' => 'Jumlah produk melebihi stok yang tersedia.'
            ], 422);
        }
        $keranjang[$produk->id]['jumlah'] = $jumlahBaru;
        session()->put('keranjang', $keranjang);
        return response()->json([
            'success' => true,
            'jumlah' => $jumlahBaru,
            'jumlah_keranjang' => collect($keranjang)->sum('jumlah'),
        ]);
    }

    public function jumlah(Request $request){
    $request->validate([
        'produk_id' => ['required', 'integer', 'exists:produks,id'],
        'jumlah' => ['required', 'integer', 'min:1'],
    ]);

    $produk = Produk::findOrFail($request->produk_id);

    $keranjang = session()->get('keranjang', []);
    $produkId = $produk->id;

    if (!isset($keranjang[$produkId])){
        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan di keranjang.'
        ], 404);
    }

    $jumlahBaru = (int) $request->jumlah;

    if ($jumlahBaru > $produk->stok){
        return response()->json([
            'success' => false,
            'message' => 'Jumlah produk melebihi stok yang tersedia. Stok tersedia: ' . $produk->stok
        ], 422);
    }

    $keranjang[$produkId]['jumlah'] = $jumlahBaru;

    session()->put('keranjang', $keranjang);

    return response()->json([
        'success' => true,
        'jumlah' => $jumlahBaru,
        'jumlah_keranjang' => collect($keranjang)->sum('jumlah'),
    ]);
}

    public function kurang(Request $request){
        $request->validate([
            'produk_id' => ['required', 'integer', 'exists:produks,id'],
        ]);
        $keranjang = session()->get('keranjang', []);
        $produkId = $request->produk_id;
        if (!isset($keranjang[$produkId])){
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan di keranjang.'
            ], 404);
        }
        if ($keranjang[$produkId]['jumlah'] > 1){
            $keranjang[$produkId]['jumlah']--;
        }else{
            unset($keranjang[$produkId]);
        }
        session()->put('keranjang', $keranjang);
        return response()->json([
            'success' => true,
            'jumlah' => isset($keranjang[$produkId]) ? $keranjang[$produkId]['jumlah'] : 0,
            'jumlah_keranjang' => collect($keranjang)->sum('jumlah'),
        ]);
    }

    public function hapus(Request $request){
        $request->validate([
            'produk_id' => ['required', 'integer', 'exists:produks,id'],
        ]);
        $keranjang = session()->get('keranjang', []);
        $produkId = $request->produk_id;
        if (isset($keranjang[$produkId])){
            unset($keranjang[$produkId]);
        }
        session()->put('keranjang', $keranjang);
        return response()->json([
            'success' => true,
            'jumlah_keranjang' => collect($keranjang)->sum('jumlah'),
        ]);
    }

    public function kosongkan(){
        session()->forget('keranjang');
        return redirect()->route('keranjang.index')->with('success', 'Keranjang berhasil dikosongkan');
    }
    
    public function data(){
        $keranjang = session()->get('keranjang', []);
        $produkIds = array_keys($keranjang);
        $produk = Produk::with('promoAktif')->whereIn('id', $produkIds)->get();
        $data = [];
        foreach ($produk as $item){
            $hargaNormal = (float) $item->harga;
            $hargaSaatIni = $hargaNormal;
            $promo = $item->promoAktif;
            if ($promo){
                if ($promo->tipe_potongan === 'persentase'){
                    $hargaSaatIni = $hargaNormal - ($hargaNormal * $promo->besar_potongan / 100);
                }else{
                    $hargaSaatIni = $hargaNormal - $promo->besar_potongan;
                }
                $hargaSaatIni = max(0, $hargaSaatIni);
            }
            $data[$item->id] = [
                'produk_id' => $item->id,
                'jumlah' => $keranjang[$item->id]['jumlah'] ?? 0,
                'harga_normal' => $hargaNormal,
                'harga_saat_ini' => $hargaSaatIni,
                'promo_aktif' => $promo ? true : false,
                'promo_id' => $promo?->id,
                'stok' => $item->stok,
            ];
        }
        return response()->json([
            'success' => true,
            'keranjang' => $data,
        ]);
    }

    public function simpanPengiriman(Request $request){
        $request->validate([
            'metode' => 'required|in:kilat,hemat',
            'tanggal' => 'nullable|string',
            'waktu' => 'nullable|string',
        ]);
        $ongkir = $request->metode === 'kilat' ? 5000 : 0;
        session()->put('pengiriman',[
            'metode' => $request->metode,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'ongkir' => $ongkir,
        ]);
        return response()->json([
            'pesan' => 'Pilihan pengiriman berhasil disimpan',
            'pengiriman' => session('pengiriman'),
        ]);
    }
}