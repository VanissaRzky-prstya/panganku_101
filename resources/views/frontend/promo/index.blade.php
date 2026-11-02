@extends('frontend.layouts.app')
@section('title', 'Promo - Panganku')
@section('content')
<section class="bg-white text-gray-900 min-h-screen">
    <div class="w-full max-w-7xl mx-auto px-16 sm:px-6 lg:px-8 pt-7 pb-8">
        <div class="mb-12">
            @include('frontend.components.breadcrumb', ['breadcrumbs' => [['label' => 'Promo','url' => null]]])
        </div>
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
            <div>
                <p class="text-xs uppercase tracking text-gray-500 font-semibold">Penawaran Terbaik</p>
                <h1 class="mt-3 text-4xl text-black md:text-5xl font-bold">Promo Pilihan</h1>
                <p class="mt-3 text-gray-800 max-w-2xl">Nikmati harga spesial di setiap promonya</p>
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-6 pb-16">
        @if ($promo->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-5">
                @foreach ($promo as $item)
                    @php
                        $produk = $item->produk;
                        $hargaAwal = $produk->harga;
                        if ($item->tipe_potongan === 'persentase'){
                            $hargaPromo = $hargaAwal - ($hargaAwal * $item->besar_potongan / 100);
                        }else{
                            $hargaPromo = $hargaAwal - $item->besar_potongan;
                        }
                        $hargaPromo = max(0, $hargaPromo);
                    @endphp
                    <div class="group bg-white text-black rounded-2xl overflow-hidden border border-gray-200 shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-2xl">
                        <a href="{{ route('promo.detail_promo', $item->id) }}" class="block">
                            <div class="relative aspect-square bg-gray-100 overflow-hidden">
                                @if ($produk && $produk->foto_produk)
                                    <img src="{{ route('produk.foto', ['filename' => basename($produk->foto_produk)]) }}"alt="{{ $produk->nama_produk }}" 
                                    class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">Tidak ada foto</div>
                                @endif
                                <div class="absolute top-3 right-3 bg-green-500 text-black font-bold text-xs px-3 py-2 rounded-full shadow-md">
                                    @if ($item->tipe_potongan === 'persentase')
                                        {{ rtrim(rtrim(number_format($item->besar_potongan, 2, ',', '.'), '0'), ',') }}%
                                    @else
                                        Hemat Rp {{ number_format($item->besar_potongan, 0, ',', '.') }}
                                    @endif
                                </div>
                            </div>
                        </a>
                        <div class="p-4">
                            <a href="{{ route('promo.detail_promo', $item->id) }}" class="block">
                                <h2 class="font-semibold text-sm leading-snug line-clamp-2 min-h-[40px] hover:text-green-600 transition">{{ $produk->nama_produk }}</h2>
                            </a>
                            <p class="mt-4 text-xs text-gray-400 line-through">
                                Rp {{ number_format($hargaAwal, 0, ',', '.') }}</p>
                            <p class="mt-1 text-lg font-bold text-green-600">Rp {{ number_format($hargaPromo, 0, ',', '.') }}</p>
                            <button type="button" class="tombol-tambah block w-full bg-green-500 text-black py-2 rounded-lg text-sm font-semibold text-center hover:bg-gray-200 transition" 
                            data-produk-id="{{ $produk->id }}">
                                + Tambah
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($promo->hasPages())
                <div class="mt-12 flex justify-center">{{ $promo->links() }}</div>
            @endif
        @else
            <div class="py-24 text-center border border-gray-800 rounded-2xl">
                <div class="text-5xl text-green-500">%</div>
                <h2 class="mt-5 text-2xl font-semibold">Belum Ada Promo Saat Ini</h2>
                <p class="mt-2 text-gray-500">Saat ini belum ada produk yang sedang promo</p>
                <a href="{{ route('produk.index') }}" class="inline-flex mt-6 px-6 py-3 rounded-xl bg-green-500 text-black font-semibold hover:bg-green-400 transition">
                    Lihat Semua Produk
                </a>
            </div>
        @endif
    </div>
</section>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tombolTambah = document.querySelectorAll('.tombol-tambah');
    tombolTambah.forEach(function (tombol) {
        tombol.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            tombol.outerHTML = `
                <div class="kontrol-jumlah w-full flex items-center justify-between bg-green-500 text-black rounded-lg overflow-hidden font-semibold">
                    <button type="button" class="tombol-kurang w-10 py-2 text-lg font-bold hover:bg-green-600 transition">−</button>
                    <span class="jumlah-produk min-w-[30px] text-center py-2">1</span>
                    <button type="button" class="tombol-lebih w-10 py-2 text-lg font-bold hover:bg-green-600 transition">+</button>
                </div>
            `;
            const kontrol = tombol.parentElement.querySelector('.kontrol-jumlah');
            const tombolKurang = kontrol.querySelector('.tombol-kurang');
            const tombolLebih = kontrol.querySelector('.tombol-lebih');
            const jumlah = kontrol.querySelector('.jumlah-produk');
            let nilai = 1;
            tombolLebih.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                nilai++;
                jumlah.textContent = nilai;
            });
            tombolKurang.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                if (nilai > 1) {
                    nilai--;
                    jumlah.textContent = nilai;
                } else {
                    kontrol.outerHTML = `
                        <button type="button" class="tombol-tambah w-full bg-green-500 text-black py-2 rounded-lg text-sm font-semibold text-center hover:bg-gray-200 transition">
                            + Tambah
                        </button>
                    `;
                    const tombolBaru = kontrol.parentElement.querySelector('.tombol-tambah');
                    tombolBaru.addEventListener('click', function (event) {
                        event.preventDefault();
                        event.stopPropagation();
                    });
                }
            });
        });
    });
});
</script>
@endpush
@endsection