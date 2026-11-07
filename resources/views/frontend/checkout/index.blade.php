@extends('frontend.layouts.app')
@section('title', 'Detail Pesanan - Panganku')
@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-8 py-6 sm:py-9">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('keranjang.index') }}" class="w-9 h-9 flex-shrink-0 flex items-center justify-center rounded-full bg-white border border-gray-200 hover:bg-gray-100 transition" aria-label="Kembali ke keranjang">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Detail Pesanan</h1>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 md:p-6 mb-5">
        <div class="flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined icon-location_on">location_on</span>
            <h2 class="font-semibold text-gray-900">Alamat Pengiriman</h2>
        </div>
        <div class="text-sm text-gray-700 space-y-1">
            <p class="font-semibold text-gray-900">{{ $alamat->nama_penerima }}</p>
            <p class="text-gray-500">{{ $alamat->nomor_telepon }}</p>
            <p>
                {{ $alamat->provinsi }},
                {{ $alamat->kabupaten_kota }},
                {{ $alamat->kecamatan }},
                {{ $alamat->kelurahan_desa }}
            </p>
            <p class="text-gray-700">{{ $alamat->alamat_lengkap }}</p>
            @if ($alamat->patokan)
                <p class="text-gray-500">
                    Patokan: {{ $alamat->patokan }}
                </p>
            @endif
        </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 md:p-6 mb-5">
        <div class="flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined icon-hand_package">hand_package</span>
            <h2 class="font-semibold text-gray-900">Pengiriman</h2>
        </div>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center flex-shrink-0">
                    @if ($pengiriman['metode'] === 'kilat')
                        <span class="material-symbols-outlined icon-rocket_launch text-green-600">rocket_launch</span>
                    @else
                        <span class="material-symbols-outlined icon-rocket_launch text-green-600">package_2</span>
                    @endif
                </div>
                <div>
                    <p class="font-semibold text-sm text-gray-900">Pengiriman</p>
                    <p class="text-sm text-gray-500">
                        @if ($pengiriman['metode'] === 'kilat')
                            Kilat
                        @else
                            Hemat
                            @if (!empty($pengiriman['waktu']))
                                • {{ $pengiriman['waktu'] }}
                            @endif
                        @endif
                    </p>
                </div>
            </div>
            <span class="text-sm text-gray-400">
                @if ($pengiriman['ongkir'] > 0)
                    Rp {{ number_format($pengiriman['ongkir'], 0, ',', '.') }}
                @else
                    -
                @endif
            </span>
        </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 md:p-6 mb-5">
        <div class="flex items-center gap-2 mb-5">
            <span class="material-symbols-outlined icon-shopping_basket">shopping_basket</span>
            <h2 class="font-semibold text-gray-900">Produk Pesanan</h2>
        </div>
        @php
            $subtotal = 0;
        @endphp
        <div class="divide-y divide-gray-100">
            @foreach ($keranjang as $produkId => $item)
                @php
                    $dataProduk = $produk->get($produkId);
                    if (!$dataProduk) {
                        continue;
                    }
                    $hargaNormal = $dataProduk->harga;
                    $jumlah = $item['jumlah'] ?? 0;
                    $subtotalProduk = $hargaNormal * $jumlah;
                    $subtotal += $subtotalProduk;
                @endphp
                <div class="py-5 first:pt-0 last:pb-0">
                    <div class="flex gap-4">
                        <a href="{{ route('produk.detail', $dataProduk->slug) }}" class="w-20 h-20 md:w-24 md:h-24 flex-shrink-0 rounded-xl overflow-hidden bg-gray-100">
                            @if ($dataProduk->foto_produk)
                                <img src="{{ route('produk.foto', ['filename' => basename($dataProduk->foto_produk)]) }}" alt="{{ $dataProduk->nama_produk }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <span class="material-symbols-outlined text-3xl">image</span>
                                </div>
                            @endif
                        </a>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('produk.detail', $dataProduk->slug) }}" class="font-semibold text-sm md:text-base text-gray-900 hover:text-green-600 transition">
                                {{ $dataProduk->nama_produk }}
                            </a>
                            @if ($dataProduk->berat && $dataProduk->satuan)
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $dataProduk->berat }}
                                    {{ $dataProduk->satuan }}
                                </p>
                            @endif
                            <p class="text-sm text-gray-500 mt-1">{{ $jumlah }} produk</p>
                            <div class="mt-2">
                                <p class="text-sm md:text-base font-bold text-green-600">Rp {{ number_format($hargaNormal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs text-gray-400">Subtotal</p>
                            <p class="text-sm md:text-base font-bold text-gray-900 mt-1">Rp {{ number_format($subtotalProduk, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 md:p-6 mb-5">
        <div class="flex items-center gap-2 mb-2">
            <span class="material-symbols-outlined icon-receipt_long">receipt_long</span>
            <h2 class="font-semibold text-gray-900 mb-4">Ringkasan Pembayaran</h2>
        </div>
        <div class="space-y-3 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Subtotal</span>
                <span class="font-medium text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Ongkos Kirim</span>
                @if ($ongkir > 0)
                    <span class="font-medium text-gray-900">
                        Rp {{ number_format($pengiriman['ongkir'], 0, ',', '.') }}
                    </span>
                @else
                <span class="font-medium text-gray-400">-</span>
                @endif
            </div>
            <div class="border-t border-gray-100 pt-3 flex items-center justify-between">
                <span class="font-semibold text-gray-900">Total Bayar</span>
                <span class="font-bold text-lg text-green-600">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 md:p-6 mb-6">
        <div class="flex items-center gap-2 mb-5">
            <span class="material-symbols-outlined icon-payments">payments</span>
            <div>
                <h2 class="font-semibold text-gray-900">Metode Pembayaran</h2>
                <p class="text-xs text-gray-500 mt-0.5">Pilih metode pembayaran yang kamu mau</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <button type="button" onclick="pilihMetodePembayaran('bank')" id="metodeBank" class="metode-pembayaran w-full text-left border border-gray-200 rounded-xl p-4 transition hover:border-green-500">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center">
                            <span class="material-symbols-outlined text-gray-700">account_balance</span>
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-gray-900">Transfer Bank</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-gray-300 text-xl">arrow_drop_down</span>
                </div>
            </button>
            <button type="button" onclick="pilihMetodePembayaran('ewallet')" id="metodeEwallet" class="metode-pembayaran w-full text-left border border-gray-200 rounded-xl p-4 transition hover:border-green-500">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center">
                            <span class="material-symbols-outlined text-gray-700">account_balance_wallet</span>
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-gray-900">E-Wallet</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-gray-300 text-xl">arrow_drop_down</span>
                </div>
            </button>
        </div>
        <div id="detailBank" class="hidden mt-5 border border-green-200 bg-green-50/40 rounded-xl p-5">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <button type="button" onclick="pilihBank('BRI')" id="bankBRI" class="bank-pilihan border border-gray-200 bg-white rounded-xl p-3 h-18 flex items-center justify-center hover:border-green-500 transition">
                    <img src="{{ asset('images/pembayaran/bri.jpg') }}" alt="BRI" class="max-h-17 max-w-full object-contain">
                </button>
                <button type="button" onclick="pilihBank('BCA')" id="bankBCA" class="bank-pilihan border border-gray-200 bg-white rounded-xl p-3 h-18 flex items-center justify-center hover:border-green-500 transition">
                    <img src="{{ asset('images/pembayaran/bca.jpg') }}" alt="BCA" class="max-h-17 max-w-full object-contain">
                </button>
                <button type="button" onclick="pilihBank('BNI')" id="bankBNI" class="bank-pilihan border border-gray-200 bg-white rounded-xl p-3 h-18 flex items-center justify-center hover:border-green-500 transition">
                    <img src="{{ asset('images/pembayaran/bni.jpg') }}" alt="BNI" class="max-h-17 max-w-full object-contain">
                </button>
                <button type="button" onclick="pilihBank('Mandiri')" id="bankMandiri" class="bank-pilihan border border-gray-200 bg-white rounded-xl p-3 h-18 flex items-center justify-center hover:border-green-500 transition">
                    <img src="{{ asset('images/pembayaran/mandiri.jpg') }}" alt="Mandiri" class="max-h-17 max-w-full object-contain">
                </button>
            </div>
        </div>
        <div id="detailEwallet" class="hidden mt-5 border border-green-200 bg-green-50/40 rounded-xl p-5">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <button type="button" onclick="pilihEwallet('GoPay')" id="ewalletGoPay" class="ewallet-pilihan border border-gray-200 bg-white rounded-xl p-3 h-18 flex items-center justify-center hover:border-green-500 transition">
                    <img src="{{ asset('images/pembayaran/gopay.jpg') }}" alt="GoPay" class="max-h-17 max-w-full object-contain">
                </button>
            </div>
        </div>
        <button type="button" id="tombolBayar" disabled class="w-full mt-6 bg-green-600 text-white py-3 rounded-xl font-semibold hover:bg-green-700 transition disabled:bg-gray-300 disabled:cursor-not-allowed">Lanjut ke Pembayaran</button>
    </div>
</div>
<div id="popupPembayaranSukses" class="popup-sukses-overlay" style="display: none;">
    <div class="popup-sukses-card">
        <div class="popup-sukses-header">
            <div class="ikon-sukses">✓</div>
            <h2>Terima kasih sudah berbelanja di Panganku!</h2>
            <p>Pembayaran kamu berhasil diproses</p>
        </div>
        <div class="popup-sukses-body">
            <p class="label-pesanan">Kode Pesanan</p>
            <h3 id="popupKodePesanan">-</h3>
            <div class="ringkasan-baris">
                <span>Subtotal</span>
                <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
            </div>
            <div class="ringkasan-baris">
                <span>Ongkos Kirim</span>
                <strong>Rp {{ number_format($ongkir, 0, ',', '.') }}</strong>
            </div>
            <div class="ringkasan-total">
                <strong>Total Pembayaran</strong>
                <strong>Rp {{ number_format($totalBayar, 0, ',', '.') }}</strong>
            </div>
            <a href="{{ url('/') }}" class="btn-lanjut-belanja">Lanjut Belanja</a>
            <a href="{{ route('pesanan.index') }}" class="btn-riwayat-pesanan">Lihat Pesanan</a>
        </div>
    </div>
</div>
<script>
    let metodePembayaranDipilih = null;
    let bankDipilih = null;
    let ewalletDipilih = null;
    function pilihMetodePembayaran(metode){
        metodePembayaranDipilih = metode;
        const detailBank = document.getElementById('detailBank');
        const detailEwallet = document.getElementById('detailEwallet');
        detailBank.classList.add('hidden');
        detailEwallet.classList.add('hidden');
        document.querySelectorAll('.metode-pembayaran').forEach(function (element){
            element.classList.remove(
                'border-green-500',
                'bg-green-50'
            );
            element.classList.add('border-gray-200');
        });
        if (metode === 'bank'){
            detailBank.classList.remove('hidden');
            document.getElementById('metodeBank').classList.add(
                'border-green-500',
                'bg-green-50'
            );
            bankDipilih = null;
            nonaktifkanTombolBayar();
        }
        if (metode === 'ewallet'){
            detailEwallet.classList.remove('hidden');
            document.getElementById('metodeEwallet').classList.add(
                'border-green-500',
                'bg-green-50'
            );
            ewalletDipilih = null;
            document.querySelectorAll('.ewallet-pilihan').forEach(function(element) {
                element.classList.remove('border-green-500', 'bg-green-50', 'text-green-700');
            });
            nonaktifkanTombolBayar();
        }
    }

    function pilihBank(bank){
        bankDipilih = bank;
        document.querySelectorAll('.bank-pilihan').forEach(function (element){
            element.classList.remove('border-green-500', 'bg-green-50', 'text-green-700');
        });
        let tombolBank = null;
        if (bank === 'BRI'){
            tombolBank = document.getElementById('bankBRI');
        }
        if (bank === 'BCA'){
            tombolBank = document.getElementById('bankBCA');
        }
        if (bank === 'BNI'){
            tombolBank = document.getElementById('bankBNI');
        }
        if (bank === 'Mandiri'){
            tombolBank = document.getElementById('bankMandiri');
        }
        if (tombolBank){
            tombolBank.classList.add('border-green-500', 'bg-green-50', 'text-green-700');
        }
        aktifkanTombolBayar('Bayar dengan ' + bank);
    }

    function pilihEwallet(ewallet){
        ewalletDipilih = ewallet;
        document.querySelectorAll('.ewallet-pilihan').forEach(function (element){
            element.classList.remove('border-green-500', 'bg-green-50', 'text-green-700');
        });
        const idEwallet = {
            GoPay: 'ewalletGoPay',
        };
        const tombolEwallet = document.getElementById(idEwallet[ewallet]);
        if (tombolEwallet){
            tombolEwallet.classList.add('border-green-500', 'bg-green-50', 'text-green-700');
        }
        aktifkanTombolBayar('Bayar dengan ' + ewallet);
    }

    function aktifkanTombolBayar(teks){
        const tombolBayar = document.getElementById('tombolBayar');
        tombolBayar.disabled = false;
        tombolBayar.textContent = teks;
    }
    function nonaktifkanTombolBayar(){
        const tombolBayar = document.getElementById('tombolBayar');
        tombolBayar.disabled = true;
        tombolBayar.textContent = 'Pilih Metode Pembayaran';
    }
    document.getElementById('tombolBayar').addEventListener('click', function(){
        const dataPembayaran = {
            metode: metodePembayaranDipilih,
            bank: bankDipilih,
            ewallet: ewalletDipilih
        };
        fetch('{{ route('checkout.proses') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(dataPembayaran)
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                console.error('ERROR DARI LARAVEL:', data);
                throw new Error(data.error || data.pesan || 'Server gagal memproses pembayaran');
            }
            return data;
        })
        .then(data => {
            console.log(data);
            if(data.snap_token){
                snap.pay(data.snap_token,{
                    onSuccess: function(result){
                        document.getElementById('popupKodePesanan').textContent = result.order_id || '-';
                        document.getElementById('popupPembayaranSukses').style.display = 'flex';
                    },
                    onPending: function(result){
                        console.log('Pembayaran pending:', result);
                        alert('Pembayaran akan tertunda jika anda keluar');
                    },
                    onError: function(result){
                        console.error('Pembayaran gagal:', result);
                        alert('Pembayaran gagal');
                    },
                    onClose: function(){
                        console.log('Popup pembayaran ditutup');
                    }
                });
            }else{
                alert(data.pesan || 'Gagal mendapatkan token pembayaran');
            }
        })
        .catch(error => {
            console.error('ERROR CHECKOUT:', error);
            alert('Gagal Melakukan Pembayaran' + error.message );
        });
    });
</script>
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>
@endsection
