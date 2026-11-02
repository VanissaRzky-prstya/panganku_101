@extends('frontend.layouts.app')
@section('title', $promo->produk->nama_produk . ' - Panganku')
@section('content')
@php
    \Carbon\Carbon::setLocale('id');
@endphp
<section class="bg-black text-white min-h-screen">
    <div class="max-w-7xl mx-auto px-6 py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-16">
            <div>
                <div class="relative aspect-square bg-gray-100 rounded-2xl overflow-hidden">
                    @if ($promo->produk->foto_produk)
                        <img src="{{ route('produk.foto', ['filename' => basename($promo->produk->foto_produk)]) }}" 
                        alt="{{ $promo->produk->nama_produk }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">Tidak ada foto</div>
                    @endif
                    @php
                        if ($promo->tipe_potongan === 'persentase'){
                            $persentasePromo = $promo->besar_potongan;
                        }else{
                            $persentasePromo = $promo->produk->harga > 0 ? round(($promo->besar_potongan / $promo->produk->harga) * 100) : 0;
                        }
                    @endphp
                    <div class="absolute top-4 left-4 z-10 bg-green-500 text-white px-4 py-2 rounded-full text-sm font-bold shadow-md">
                        {{ number_format($persentasePromo, 0) }}% OFF
                    </div>
                </div>
            </div>
            <div class="flex flex-col justify-center">
                @if ($promo->produk->subkategori && $promo->produk->subkategori->kategori)
                    <p class="text-xs uppercase tracking-[0.2em] text-green-500">{{ $promo->produk->subkategori->kategori->nama_kategori }}</p>
                @endif
                @if ($promo->produk->subkategori)
                    <p class="mt-2 text-sm text-gray-500">{{ $promo->produk->subkategori->nama_subkategori }}</p>
                @endif
                <h1 class="mt-3 text-3xl md:text-4xl font-bold">{{ $promo->produk->nama_produk }}</h1>
                <div class="mt-6">
                    @php
                        $hargaAwal = $promo->produk->harga;
                        if ($promo->tipe_potongan === 'persentase'){
                            $hargaPromo = $hargaAwal - ($hargaAwal * $promo->besar_potongan / 100);
                        }else{
                            $hargaPromo = $hargaAwal - $promo->besar_potongan;
                        }
                        $hargaPromo = max(0, $hargaPromo);
                    @endphp
                    <p class="text-3xl font-bold text-green-500">Rp {{ number_format($hargaPromo, 0, ',', '.') }}</p>
                    <p class="mt-1 text-sm text-gray-500 line-through">Rp {{ number_format($hargaAwal, 0, ',', '.') }}</p>
                    @if ($promo->produk->berat && $promo->produk->satuan)
                        <p class="mt-2 text-sm text-gray-500">{{ $promo->produk->berat }} {{ $promo->produk->satuan }}</p>
                    @endif
                </div>
                <div class="mt-6 p-4 rounded-xl border border-green-500/20 bg-green-500/5">
                    <p class="text-xs uppercase tracking-wider text-green-500">Periode Harga Spesial</p>
                    <p class="mt-2 text-sm text-gray-300">
                        {{ \Carbon\Carbon::parse($promo->tanggal_mulai)->translatedFormat('d F Y') }}
                        <span class="text-green-500 mx-1">→</span>
                        {{ \Carbon\Carbon::parse($promo->tanggal_berakhir)->translatedFormat('d F Y') }}
                    </p>
                </div>
                @if ($promo->produk->deskripsi)
                    <div class="mt-8">
                        <h2 class="text-lg font-semibold">Deskripsi Produk</h2>
                        <p class="mt-3 text-gray-400 leading-relaxed">{{ $promo->produk->deskripsi }}</p>
                    </div>
                @endif
                <div class="mt-8">
                    <button type="button" class="tombol-tambah block w-full bg-green-500 text-black py-2 rounded-lg text-sm font-semibold text-center hover:bg-gray-200 transition" data-produk-id="{{ $promo->produk->id }}">
                        + Tambah
                    </button>
                </div>
            </div>
        </div>
        @if ($produkSerupa->count())
            <div class="mt-20">
                <div class="flex items-end justify-between mb-7">
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-gray-500">Pilihan lainnya</p>
                        <h2 class="mt-2 text-2xl md:text-3xl font-bold">Produk Serupa</h2>
                    </div>
                    @if ($produkSerupa->hasPages())
                        <div class="flex items-center gap-2">
                            @if ($produkSerupa->onFirstPage())
                                <span class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-800 text-gray-700 cursor-not-allowed"> ‹ </span>
                            @else
                                <a href="{{ $produkSerupa->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-700 text-white hover:border-green-500 hover:text-green-500 transition"> ‹ </a>
                            @endif
                            @if ($produkSerupa->hasMorePages())
                                <a href="{{ $produkSerupa->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-700 text-white hover:border-green-500 hover:text-green-500 transition"> › </a>
                            @else
                                <span class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-800 text-gray-700 cursor-not-allowed"> › </span>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                    @foreach ($produkSerupa as $produkItem)
                        @php
                            $promoSerupa = $produkItem->promos->first();
                            $hargaAwalSerupa = $produkItem->harga;
                            $hargaPromoSerupa = null;
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
                            <a href="{{ $promoSerupa ? route('promo.detail_promo', $promoSerupa->id) : route('produk.detail', $produkItem->slug)}}" 
                            class="block bg-white text-black rounded-xl overflow-hidden shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                                <div class="relative aspect-square bg-gray-100 overflow-hidden">
                                    @if ($produkItem->foto_produk)
                                        <img src="{{ route('produk.foto', ['filename' => basename($produkItem->foto_produk)]) }}" 
                                        alt="{{ $produkItem->nama_produk }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">Tidak ada foto</div>
                                    @endif
                                    @if ($promoSerupa)
                                        @php
                                            if ($promoSerupa->tipe_potongan === 'persentase') {
                                                $persentasePromoSerupa = $promoSerupa->besar_potongan;
                                            }else{
                                                $persentasePromoSerupa = $hargaAwalSerupa > 0 ? round(($promoSerupa->besar_potongan / $hargaAwalSerupa) * 100) : 0;
                                            }
                                        @endphp
                                        <div class="absolute top-3 left-3 z-10 bg-green-500 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-md">
                                            {{ number_format($persentasePromoSerupa, 0) }}% OFF
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4 flex flex-col">
                                    <h3 class="text-sm font-medium leading-snug line-clamp-2 min-h-[40px]">{{ $produkItem->nama_produk }}</h3>
                                    <div class="mt-2 min-h-[42px]">
                                        @if ($promoSerupa)
                                            <p class="text-base font-bold text-green-600">Rp {{ number_format($hargaPromoSerupa, 0, ',', '.') }}</p>
                                            <p class="text-xs text-gray-400 line-through">Rp {{ number_format($hargaAwalSerupa, 0, ',', '.') }}</p>
                                        @else
                                            <p class="text-base font-bold text-green-600">Rp {{ number_format($hargaAwalSerupa, 0, ',', '.') }}</p>
                                        @endif
                                    </div>
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
            </div>
        @endif
    </div>
</section>
@endsection