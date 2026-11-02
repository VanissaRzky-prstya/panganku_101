@extends('frontend.layouts.app')
@section('title', $produk->nama_produk . ' - Panganku')
@section('content')
<section class="bg-white text-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-6 py-9">
        <div class="flex items-center ml-6 gap-6 mb-2">
            <a href="{{ route('beranda') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 hover:bg-gray-100">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-10 pt-4">
            <div>
                <div class="relative aspect-square bg-gray-100 rounded-2xl overflow-hidden">
                    @if ($produk->foto_produk)
                        <img src="{{ route('produk.foto', ['filename' => basename($produk->foto_produk)]) }}" alt="{{ $produk->nama_produk }}" 
                        class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">Tidak ada foto</div>
                    @endif
                    @if ($produk->promoAktif)
                        @php
                            $promoProduk = $produk->promoAktif;
                            if ($promoProduk->tipe_potongan === 'persentase'){
                                $persentasePromo = $promoProduk->besar_potongan;
                            }else{
                                $persentasePromo = $produk->harga > 0 ? round(($promoProduk->besar_potongan / $produk->harga) * 100) : 0;
                            }
                        @endphp
                        <div class="absolute top-4 left-4 z-10 bg-green-500 text-white px-4 py-2 rounded-full text-sm font-bold shadow-md">
                            {{ number_format($persentasePromo, 0) }}% OFF
                        </div>
                    @endif
                </div>
            </div>
            <div class="flex flex-col justify-center">
                @if ($produk->subkategori && $produk->subkategori->kategori)
                    <p class="text-xs uppercase tracking-[0.2em] text-green-500">{{ $produk->subkategori->kategori->nama_kategori }}</p>
                @endif
                @if ($produk->subkategori)
                    <p class="mt-2 text-sm text-gray-500">{{ $produk->subkategori->nama_subkategori }}</p>
                @endif
                    <h1 class="mt-3 text-3xl md:text-4xl font-bold">{{ $produk->nama_produk }}</h1>
                    <div class="mt-6">
                        @php
                            $promoProduk = $produk->promoAktif;
                            $hargaAwal = $produk->harga;
                            $hargaPromo = $hargaAwal;
                            if ($promoProduk){
                                if ($promoProduk->tipe_potongan === 'persentase') {
                                    $hargaPromo = $hargaAwal - ($hargaAwal * $promoProduk->besar_potongan / 100);
                                }else{
                                    $hargaPromo = $hargaAwal - $promoProduk->besar_potongan;
                                }
                                $hargaPromo = max(0, $hargaPromo);
                            }
                        @endphp
                        @if ($promoProduk)
                            <p class="text-3xl font-bold text-green-500">Rp {{ number_format($hargaPromo, 0, ',', '.') }}</p>
                            <p class="mt-1 text-sm text-gray-500 line-through">Rp {{ number_format($hargaAwal, 0, ',', '.') }}</p>
                        @else
                            <p class="text-3xl font-bold text-green-500">Rp {{ number_format($hargaAwal, 0, ',', '.') }}</p>
                        @endif
                        @if ($produk->berat && $produk->satuan)
                            <p class="mt-2 text-sm text-gray-500">{{ $produk->berat }} {{ $produk->satuan }}</p>
                        @endif
                    </div>
                    @if ($produk->deskripsi)
                        <div class="mt-8">
                            <h2 class="text-lg font-semibold">Deskripsi Produk</h2>
                            <p class="mt-3 text-gray-600 leading-relaxed">{{ $produk->deskripsi }}</p>
                        </div>
                    @endif
                    <div class="mt-8">
                        <button type="button" class="tombol-tambah block w-full bg-green-500 text-black py-2 rounded-lg text-sm font-semibold text-center hover:bg-gray-200 transition" 
                        data-produk-id="{{ $produk->id }}">
                            + Tambah
                        </button>
                    </div>
                </div>
            </div>
            @if ($produkSerupa->count())
                <div class="mt-20">
                    <div class="mb-6">
                        <p class="text-xs uppercase tracking-[0.25em] text-gray-500">Rekomendasi</p>
                        <h2 class="text-2xl md:text-3xl font-bold mt-2">Produk Serupa</h2>
                        <p class="mt-2 text-sm text-gray-600"> Produk lain dalam kategori {{ $produk->subkategori->nama_subkategori ?? 'yang sama' }}</p>
                    </div>
                    <div id="produkSerupaCarousel" class="relative">
                        <button id="produkSerupaPrev" type="button" 
                        class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1/2 z-10 w-11 h-11 rounded-full bg-white text-black shadow-lg flex items-center justify-center text-2xl font-light hover:bg-gray-100 transition disabled:opacity-30 disabled:cursor-not-allowed" disabled>
                            ‹
                        </button>
                        <div id="produkSerupaTrack" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                            @foreach ($produkSerupa as $produkItem)
                                @php
                                    $promoSerupa = $produkItem->promoAktif;
                                    $hargaAwalSerupa = $produkItem->harga;
                                    $hargaPromoSerupa = $hargaAwalSerupa;
                                    if ($promoSerupa){
                                        if ($promoSerupa->tipe_potongan === 'persentase'){
                                            $hargaPromoSerupa = $hargaAwalSerupa - ($hargaAwalSerupa * $promoSerupa->besar_potongan / 100);
                                        }else{
                                            $hargaPromoSerupa = $hargaAwalSerupa - $promoSerupa->besar_potongan;
                                        }
                                        $hargaPromoSerupa = max(0, $hargaPromoSerupa);
                                    }
                                @endphp
                                <div class="produk-serupa-item bg-white text-black rounded-xl overflow-hidden shadow-lg">
                                    <a href="{{ route('produk.detail', $produkItem->slug) }}" class="block bg-white text-black rounded-xl overflow-hidden shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                                        <div class="relative aspect-square bg-gray-100 overflow-hidden">
                                            @if ($produkItem->foto_produk)
                                                <img src="{{ route('produk.foto', ['filename' => basename($produkItem->foto_produk)]) }}" alt="{{ $produkItem->nama_produk }}" 
                                                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">Tidak ada foto</div>
                                            @endif
                                            @if ($promoSerupa)
                                                @php
                                                    if ($promoSerupa->tipe_potongan === 'persentase'){
                                                        $persentasePromo = $promoSerupa->besar_potongan;
                                                    }else{
                                                        $persentasePromo = $hargaAwalSerupa > 0 ? round(($promoSerupa->besar_potongan / $hargaAwalSerupa) * 100) : 0;
                                                    }
                                                @endphp
                                                <div class="absolute top-3 left-3 z-10 bg-green-500 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-md">
                                                    {{ number_format($persentasePromo, 0) }}% OFF
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <h3 class="text-sm font-medium leading-snug line-clamp-2 min-h-[40px]">{{ $produkItem->nama_produk }}</h3>
                                            @if ($promoSerupa)
                                                <p class="mt-2 text-base font-bold text-green-600">Rp {{ number_format($hargaPromoSerupa, 0, ',', '.') }}</p>
                                                <p class="text-xs text-gray-400 line-through">Rp {{ number_format($hargaAwalSerupa, 0, ',', '.') }}</p>
                                            @else
                                                <p class="mt-2 text-base font-bold text-green-600">Rp {{ number_format($hargaAwalSerupa, 0, ',', '.') }}</p>
                                                <p class="text-xs text-transparent"> - </p>
                                            @endif
                                        </div>
                                    </a>
                                    <div class="px-4 pb-4">
                                        <button type="button" class="tombol-tambah block w-full bg-green-500 text-black py-2 rounded-lg text-sm font-semibold text-center hover:bg-gray-200 transition" 
                                        data-produk-id="{{ $produkItem->id }}">
                                            + Tambah
                                        </button>
                                    </div>  
                                </div>                                      
                            @endforeach
                        </div>
                        <button id="produkSerupaNext" type="button" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 z-10 w-11 h-11 rounded-full bg-white text-black shadow-lg flex items-center justify-center text-2xl font-light hover:bg-gray-100 transition disabled:opacity-30 disabled:cursor-not-allowed">
                            ›
                        </button>
                    </div>
                </div>
            @endif
        </div>    
        @push('scripts')
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                const items = document.querySelectorAll('.produk-serupa-item');
                const prevButton = document.getElementById('produkSerupaPrev');
                const nextButton = document.getElementById('produkSerupaNext');
                if (!items.length || !prevButton || !nextButton){
                    return;
                }                        
                let halaman = 0;
                const jumlahPerHalaman = 6;
                const jumlahHalaman = Math.ceil(items.length / jumlahPerHalaman);                    
                function tampilkanHalaman() {
                    const mulai = halaman * jumlahPerHalaman;
                    const selesai = mulai + jumlahPerHalaman;
                    items.forEach((item, index) => {
                        if (index >= mulai && index < selesai) {
                            item.classList.remove('hidden');
                        } else {
                            item.classList.add('hidden');
                        }
                    });
                    prevButton.disabled = halaman === 0;
                    nextButton.disabled = halaman >= jumlahHalaman - 1;
                }
                prevButton.addEventListener('click', function () {
                    if (halaman > 0) {
                        halaman--;
                        tampilkanHalaman();
                    }
                });
                nextButton.addEventListener('click', function () {
                    if (halaman < jumlahHalaman - 1) {
                        halaman++;
                        tampilkanHalaman();
                    }
                });
                tampilkanHalaman();
            });
            </script>
        @endpush
</section>
@endsection