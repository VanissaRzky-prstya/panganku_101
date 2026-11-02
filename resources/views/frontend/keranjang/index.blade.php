@extends('frontend.layouts.app')
@section('title', 'Keranjang - Panganku')
@section('content')
<section class="bg-white min-h-screen text-black">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8 md:py-10">
        <div class="mb-8">
            @include('frontend.components.breadcrumb', [
                'breadcrumbs' => [['label' => 'Keranjang', 'url' => null]]
            ])
        </div>
        <div class="mb-8">
            <h1 class="mt-2 text-3xl md:text-4xl font-bold text-black">Keranjang Belanja</h1>
            <p class="mt-2 text-sm text-gray-500">Mohon periksa belanja anda sebelum melakukan pemesanan</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-5 md:p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined icon-location_on">location_on</span>
                        <h3 class="font-semibold text-sm text-black">Alamat Pengiriman</h3>
                    </div>
                    @if ($alamat)
                        <div class="w-full min-h-[160px] border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-sm text-black">{{ $alamat->nama_penerima }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $alamat->nomor_telepon }}</p>
                                </div>
                                <a href="{{ route('alamat.create') }}" class="text-xs font-medium text-green-600 hover:text-green-700">
                                    Ubah
                                </a>
                            </div>  
                            <div class="mt-4 text-sm text-gray-600 leading-relaxed">
                            <p>{{ $alamat->provinsi }}, {{ $alamat->kabupaten_kota }}, {{ $alamat->kecamatan }}, {{ $alamat->kelurahan_desa }}</p>
                            <p class="mt-1 text-gray-700">{{ $alamat->alamat_lengkap }}</p>
                        </div>
                    </div>
                @else
                    <a href="{{ route('alamat.create') }}" class="w-full min-h-[160px] border border-gray-200 rounded-lg flex flex-col 
                        items-center justify-center hover:border-green-700 hover:bg-green-50/30 transition">
                        
                        <span class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center text-3xl mb-3">+</span>
                        <span class="font-semibold text-sm text-black">Tambahkan alamat pengiriman</span>
                        <span class="text-xs text-gray-500 mt-1">Belum ada alamat pengiriman</span>
                        <span class="text-xs text-gray-400 mt-2">Tambahkan alamat untuk memudahkan pengiriman pesanan Anda</span>
                    </a>
                @endif    
                </div>
                <div>
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="material-symbols-outlined icon-hand_package">hand_package</span>
                            <h3 class="font-semibold text-sm text-black">Pengiriman</h3>
                        </div>
                        <button type="button" onclick="bukaModalPengiriman()"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white flex items-center justify-between text-left hover:border-green-700 hover:bg-green-50/30 transition"
                        >
                            <span id="teksTipePengiriman" class="text-sm text-gray-700 flex gap-2">Pilih Pengiriman</span>
                        </button>
                        <div id="modalPengiriman" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
                            <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
                                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                                    <div>
                                        <h2 class="font-semibold text-lg text-black">Pilih Pengiriman</h2>
                                        <p class="text-xs text-gray-500 mt-1">Pilih metode pengiriman untuk pesanan anda</p>
                                    </div>
                                    <button type="button" onclick="tutupModalPengiriman()" class="w-8 h-8 flex items-center text-2xl justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-black transition">
                                        ×
                                    </button>
                                </div>
                                <div class="p-5 space-y-4">
                                    <button type="button" id="pilihanKilat" onclick="pilihPengiriman('kilat')"
                                        class="w-full text-left border border-gray-200 rounded-xl p-4 hover:border-green-600 hover:bg-green-50/30 transition"
                                    >
                                    <div class="flex items-start gap-3">
                                        <span class="material-symbols-outlined icon-rocket_launch">rocket_launch</span>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between gap-3">
                                                <div>
                                                    <p class="font-semibold text-sm text-black">Kilat</p>
                                                    <p class="text-xs text-gray-500 mt-1">Sampai sekitar 1 jam setelah selesai pembayaran</p>
                                                </div>
                                                <span class="font-semibold text-sm text-green-600">
                                                    Rp5.000
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    </button>
                                    <div id="pilihanHemat" class="overflow-hidden transition">
                                        <button type="button" id="tombolhemat" onclick="bukaHemat()" class="w-full text-left border border-gray-200 rounded-xl p-4 hover:border-green-600 hover:bg-green-50/30 transition">
                                            <div class="flex items-start gap-3">
                                                <span class="material-symbols-outlined icon-package_2">package_2</span>
                                                <div class="flex-1">
                                                    <div class="flex items-center justify-between">
                                                        <div>
                                                            <p class="font-semibold text-sm text-black">
                                                                Hemat
                                                            </p>
                                                            <p class="text-xs text-gray-500 mt-2">Pilih waktu pengiriman</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </button>
                                        <div id="detailHemat" class="hidden border-t border-gray-100 px-4 pb-4">
                                            <div class="mt-4">
                                                <label for="tanggalPengiriman" class="block text-xs font-medium text-gray-600 mb-2">Tanggal pengiriman</label>
                                                <div class="relative">
                                                    <input type="date" id="tanggalPengiriman" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 bg-white outline-none focus:border-green-600 focus:ring-green-600">
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <label for="waktulPengiriman" class="block text-xs font-medium text-gray-600 mb-2">Waktu pengiriman</label>
                                                <select id="waktuPengiriman" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 bg-white outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600">
                                                    <option value="">pilih waktu</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" onclick="tutupModalPengiriman()" class="w-full py-3 border bg-green-500 border-gray-200 rounded-xl text-sm font-medium text-white hover:bg-green-800 transition">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="material-symbols-outlined icon-sticky_note_2">sticky_note_2</span>
                            <h3 class="font-semibold text-sm text-black">Catatan Pengiriman</h3>
                        </div>
                        <textarea name="catatan_pengiriman" id="catatanPengiriman" rows="1" maxlength="100" placeholder="contoh: Taruh di depan pintu.."
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm text-black placeholder-gray-400 outline-none resize-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition"
                            oninput="hitungCatatan()"></textarea>
                        <div class="text-right text-xs text-gray-400 mt-1">
                            <span id="jumlahCatatan">0</span>/100
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm">{{ session('success') }}</div>
        @endif
        <div id="notifikasi-perubahan-harga" class="hidden mb-6 p-4 rounded-xl bg-yellow-50 border border-yellow-200 text-yellow-700">
            <p class="font-semibold">Harga produk berubah</p>
            <p class="mt-1 text-sm text-yellow-700/80">Harga atau promo salah satu produk di keranjang telah berubah, Silakan periksa harga terbaru sebelum melanjutkan belanja</p>
        </div>

        @if (count($keranjang) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 md:p-6">
                        <div class="flex items-center gap-2 pb-4 border-b border-gray-100">
                            <span class="material-symbols-outlined icon-shopping_basket">shopping_basket</span>
                            <h2 class="font-semibold text-base text-black">
                                Produk dalam Keranjang
                                <span class="text-gray-400 font-normal">({{ count($keranjang) }})</span>
                            </h2>
                        </div>
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-sm">
                            <label class="flex items-center gap-2 text-gray-600">
                                <input type="checkbox" checked class="accent-green-600">
                                <span>Pilih Semua</span>
                            </label>
                            <form action="{{ route('keranjang.kosongkan') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-600 transition material-symbols-outlined icon-delete">
                                    delete
                                </button>
                            </form>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach ($keranjang as $produkId => $item)
                                @php
                                    $produkItem = $produk->get($produkId);
                                    if (!$produkItem){
                                        continue;
                                    }
                                    $promoProduk = $produkItem->promoAktif;
                                    $hargaAwal = $produkItem->harga;
                                    $hargaPromo = $hargaAwal;
                                    if ($promoProduk){
                                        if ($promoProduk->tipe_potongan === 'persentase'){
                                            $hargaPromo = $hargaAwal - ($hargaAwal * $promoProduk->besar_potongan / 100);
                                        }else{
                                            $hargaPromo = $hargaAwal - $promoProduk->besar_potongan;
                                        }
                                        $hargaPromo = max(0, $hargaPromo);
                                    }
                                    $jumlah = $item['jumlah'];
                                    $subtotal = $hargaPromo * $jumlah;
                                @endphp
                                <div class="py-5" data-produk-id="{{ $produkItem->id }}" data-harga-saat-ini="{{ $hargaPromo }}" data-jumlah="{{ $jumlah }}">
                                    <div class="flex gap-4">
                                        <a href="{{ route('produk.detail', $produkItem->slug) }}" class="w-20 h-20 md:w-24 md:h-24 flex-shrink-0 rounded-xl overflow-hidden bg-gray-100">
                                            @if ($produkItem->foto_produk)
                                                <img src="{{ route('produk.foto', ['filename' => basename($produkItem->foto_produk)]) }}" alt="{{ $produkItem->nama_produk }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">Tidak ada foto</div>
                                            @endif
                                        </a>

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <a href="{{ route('produk.detail', $produkItem->slug) }}" class="font-semibold text-sm md:text-base text-black hover:text-green-600 transition">{{ $produkItem->nama_produk }}</a>
                                                    @if ($produkItem->berat && $produkItem->satuan)
                                                        <p class="mt-1 text-xs text-gray-500">
                                                            {{ $produkItem->berat }}
                                                            {{ $produkItem->satuan }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <button type="button" class="tombol-hapus text-gray-400 hover:text-red-500 transition text-xl leading-none" data-produk-id="{{ $produkItem->id }}" title="Hapus produk">
                                                    ×
                                                </button>
                                            </div>
                                            <div class="bagian-harga mt-2">
                                                @if ($promoProduk)
                                                    <p class="harga-sekarang text-sm md:text-base font-bold text-green-600">Rp {{ number_format($hargaPromo, 0, ',', '.') }}</p>
                                                    <p class="harga-normal text-xs text-gray-400 line-through">Rp {{ number_format($hargaAwal, 0, ',', '.') }}</p>
                                                @else
                                                    <p class="harga-sekarang text-sm md:text-base font-bold text-green-600">Rp {{ number_format($hargaAwal, 0, ',', '.') }}</p>
                                                @endif
                                            </div>
                                            <div class="mt-3 flex items-center justify-between gap-4">
                                                <div class="kontrol-jumlah flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                                    <button type="button" class="tombol-kurang w-8 h-8 flex items-center justify-center text-base font-semibold text-gray-600 hover:bg-gray-100 transition" data-produk-id="{{ $produkItem->id }}">
                                                        −
                                                    </button>
                                                    <span class="jumlah-produk min-w-[35px] text-center text-sm font-semibold">{{ $jumlah }}</span>
                                                    <button type="button" class="tombol-lebih w-8 h-8 flex items-center justify-center text-base font-semibold text-gray-600 hover:bg-gray-100 transition" data-produk-id="{{ $produkItem->id }}">
                                                        +
                                                    </button>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-xs text-gray-400">Subtotal</p>
                                                    <p class="subtotal-produk font-bold text-sm md:text-base text-black">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 md:p-6 lg:sticky lg:top-6">
                        <span class="material-symbols-outlined icon-receipt_long">receipt_long</span>
                        <h2 class="text-lg font-bold text-black">Ringkasan Belanja</h2>
                        @php
                            $totalHarga = 0;
                            $jumlahItem = 0;
                            foreach ($keranjang as $produkId => $item){
                                $produkItem = $produk->get($produkId);
                                if (!$produkItem){continue;}
                                $promoProduk = $produkItem->promoAktif;
                                $hargaAwal = $produkItem->harga;
                                $hargaSaatIni = $hargaAwal;
                                if ($promoProduk){
                                    if ($promoProduk->tipe_potongan === 'persentase'){
                                        $hargaSaatIni = $hargaAwal - ($hargaAwal * $promoProduk->besar_potongan / 100);
                                    }else{
                                        $hargaSaatIni = $hargaAwal - $promoProduk->besar_potongan;
                                    }
                                    $hargaSaatIni = max(0, $hargaSaatIni);
                                }
                                $totalHarga += $hargaSaatIni * $item['jumlah'];
                                $jumlahItem += $item['jumlah'];
                            }
                        @endphp
                        <div class="mt-5 space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Jumlah barang</span>
                                <span class="font-medium text-black">{{ $jumlahItem }} item</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Subtotal</span>
                                <span id="ringkasan-subtotal" class="font-medium text-black">
                                    Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Ongkos Kirim</span>
                                <span id="ringkasan-ongkir" class="font-medium text-gray-400">
                                    -
                                </span>
                            </div>
                        </div>
                        <div class="my-5 border-t border-gray-200"></div>
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-black">Total</span>
                            <span id="ringkasan-total" class="text-xl font-bold text-green-600">Rp {{ number_format($totalHarga + session('pengiriman.ongkir', 0), 0, ',', '.') }}</span>
                        </div>
                        <button type="button" id="tombolCheckout" class="mt-6 w-full bg-green-600 text-white py-3 rounded-xl font-semibold hover:bg-green-700 transition disabled:opacity-50" disabled>
                            Checkout
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-200 px-5 py-16 text-center shadow-sm">
                <div class="mx-auto mb-5 w-24 h-24 rounded-full bg-green-50 flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-600" style="font-size: 52px;">add_shopping_cart</span>
                </div>
                <h2 class="mt-5 text-2xl font-bold text-black">Keranjang Masih Kosong</h2>
                <a href="{{ route('produk.index') }}" class="inline-flex mt-6 px-6 py-3 rounded-xl bg-green-600 text-white font-semibold hover:bg-green-700 transition">Lihat Produk</a>
            </div>
        @endif
    </div>
</section>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function (){
    const modalPengiriman = document.getElementById('modalPengiriman');
    const waktuPengiriman = document.getElementById('waktuPengiriman');
    const tanggalPengiriman = document.getElementById('tanggalPengiriman');
    const teksTipePengiriman = document.getElementById('teksTipePengiriman');
    const tombolCheckout = document.getElementById('tombolCheckout');
    let pengirimanDipilih = null;
    let tanggalDipilih = '';
    let waktuDipilih = '';

    function perbaruiTombolCheckout(){
        const bisaCheckout = pengirimanDipilih !== null && (
            pengirimanDipilih === 'kilat' || (tanggalDipilih !== '' && waktuDipilih !== '')
        );
        tombolCheckout.disabled = !bisaCheckout;
    }
    tombolCheckout.addEventListener('click', function (){
        window.location.href = '{{ route('checkout.index') }}';
    });

    const ongkirPengiriman = {
        kilat: 5000,
        hemat: 0
    };
    const slotWaktu = [
        '08:00-08:59',
        '10:00-10:59',
        '12:00-12:59',
        '14:00-14:59',
        '16:00-16:59',
        '18:00-18:59',
        '20:00-20:59'
    ];
    
    window.bukaModalPengiriman = function (){
        modalPengiriman.classList.remove('hidden');
        aturTanggalPengiriman();
        isiSlotWaktu();
    };

    window.tutupModalPengiriman = function (){
        modalPengiriman.classList.add('hidden');
    };

    window.bukaHemat = function (){
        const detailHemat = document.getElementById('detailHemat');
        if (!detailHemat){
            return;
        }
        detailHemat.classList.toggle('hidden');
    };

    function formatTanggal(tanggal){
        const tahun = tanggal.getFullYear();
        const bulan = String(tanggal.getMonth() + 1).padStart(2, '0');
        const hari = String(tanggal.getDate()).padStart(2, '0');
        return `${tahun}-${bulan}-${hari}`;
    }

    function aturTanggalPengiriman(){
        const hariIni = new Date();
        hariIni.setHours(0, 0, 0, 0);
        const tanggalMaksimal = new Date(hariIni);
        tanggalMaksimal.setDate(hariIni.getDate() + 2);
        tanggalPengiriman.min = formatTanggal(hariIni);
        tanggalPengiriman.max = formatTanggal(tanggalMaksimal);
        if (!tanggalPengiriman.value){
            tanggalPengiriman.value = formatTanggal(hariIni);
        }
    }

    function isiSlotWaktu(){
        waktuPengiriman.innerHTML = '<option value="">Pilih waktu pengiriman</option>';
        const sekarang = new Date();
        const hariIniString = formatTanggal(sekarang);
        const tanggalDipilih = tanggalPengiriman.value;
        slotWaktu.forEach(function (jam){
            const jamMulai = parseInt(jam.split(':')[0]);
            let tampilkan = true;
            if (tanggalDipilih === hariIniString){
                const jamSekarang = sekarang.getHours();
                if (jamMulai <= jamSekarang){
                    tampilkan = false;
                }
            }
            if (tampilkan){
                const option = document.createElement('option');
                option.value = jam;
                option.textContent = jam;
                waktuPengiriman.appendChild(option);
            }
        });
        if (waktuPengiriman.options.length === 1){
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'Tidak ada slot tersedia';
            option.disabled = true;
            waktuPengiriman.appendChild(option);
        }
    }
    tanggalPengiriman.addEventListener('change', function (){
        isiSlotWaktu();
    });

    window.pilihPengiriman = function (jenis){
        if (jenis === 'kilat'){
            pengirimanDipilih = 'kilat';
            tanggalDipilih = '';
            waktuDipilih = '';
            teksTipePengiriman.innerHTML = `
                <span class="material-symbols-outlined icon-rocket_launch">rocket_launch</span>
                Kilat
                <span class="text-gray-400">
                    • Rp5.000
                </span>
            `;
            document.getElementById('ringkasan-ongkir').textContent = 'Rp 5.000';
            updateTotal(5000);
            perbaruiTombolCheckout();
            fetch('{{ route('keranjang.pengiriman') }}',{
            method: 'POST', headers:{
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                metode: 'kilat',
                tanggal: '',
                waktu: ''
            })
        })
        .then(response => response.json()).then(data =>{
            console.log('Pengiriman Kilat tersimpan:', data);
        })
        .catch(error =>{
            console.error('Gagal menyimpan pengiriman:', error);
        });
            tutupModalPengiriman();
            return;
        }
        if (jenis === 'hemat'){
            const waktu = waktuPengiriman.value;
            if (!waktu){
                alert('Silakan pilih waktu pengiriman terlebih dahulu');
                return;
            }
            pengirimanDipilih = 'hemat';
            tanggalDipilih = tanggalPengiriman.value;
            waktuDipilih = waktuPengiriman.value;
            fetch('{{ route('keranjang.pengiriman') }}',{
                method: 'POST', headers:{
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    metode: 'hemat',
                    tanggal: tanggalDipilih,
                    waktu: waktuDipilih
                })
            })
            .then(response => response.json()).then(data =>{
                console.log('Pengiriman Hemat tersimpan:', data);
            })
            .catch(error =>{
                console.error('Gagal menyimpan pengiriman:', error);
            });
            perbaruiTombolCheckout();

            teksTipePengiriman.innerHTML = `
                <span class="material-symbols-outlined icon-package_2">package_2</span>
                Hemat
                <span class="text-gray-400">
                    • ${waktu}
                </span>
            `;
            document.getElementById('ringkasan-ongkir').textContent = '-';
            updateTotal(0);
            tutupModalPengiriman();
        }
    };
    waktuPengiriman.addEventListener('change', function (){
        if (!this.value){
            return;
        }
        pilihPengiriman('hemat');
    });

    function updateTotal(ongkir){
        console.log('UPDATE TOTAL:', ongkir);
        const subtotalElement = document.getElementById('ringkasan-subtotal');
        const totalElement = document.getElementById('ringkasan-total');
        if (!subtotalElement || !totalElement){
            return;
        }
        const subtotal = Number(subtotalElement.textContent.replace(/[^\d]/g, ''));
        const total = subtotal + Number(ongkir);
        totalElement.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    window.hitungCatatan = function (){
        const textarea = document.getElementById('catatanPengiriman');
        const jumlah = document.getElementById('jumlahCatatan');
        if (!textarea || !jumlah){
            return;
        }
        jumlah.innerText = textarea.value.length;
    };

    function kirimKeranjang(url, produkId, method = 'POST'){
        return fetch(url,{
            method: method,headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN':
                document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({produk_id: produkId})
        });
    }

    document.querySelectorAll('.tombol-lebih').forEach(function (tombol){
        tombol.addEventListener('click', function (){
            const produkId = this.dataset.produkId;
            kirimKeranjang('{{ route('keranjang.lebih') }}',produkId).then(response => response.json()).then(data => {
                if (!data.success){
                    alert(data.message);
                    return;
                }
                location.reload();
            })
            .catch(() => {
                alert('Terjadi kesalahan, Silakan coba lagi');
            });
        });
    });

    document.querySelectorAll('.tombol-kurang').forEach(function (tombol){
        tombol.addEventListener('click', function (){
            const produkId = this.dataset.produkId;
            kirimKeranjang('{{ route('keranjang.kurang') }}',produkId)
            .then(response => response.json())
            .then(data => {
                if (!data.success){
                    alert(data.message);
                    return;
                }
                location.reload();
            })
            .catch(() => {
                alert('Terjadi kesalahan, Silakan coba lagi');
            });
        });
    });

    document.querySelectorAll('.tombol-hapus').forEach(function (tombol){
        tombol.addEventListener('click', function (){
            if (!confirm(
                'Hapus produk ini dari keranjang?'
            )){
                return;
            }
            const produkId = this.dataset.produkId;
            kirimKeranjang('{{ route('keranjang.hapus') }}',produkId,'DELETE')
            .then(response => response.json()).then(data => {
                if (!data.success){
                    alert(data.message);
                    return;
                }
                location.reload();
            })
            .catch(() => {
                alert('Terjadi kesalahan, Silakan coba lagi');
            });
        });
    });
});
</script>
@endpush
@endsection