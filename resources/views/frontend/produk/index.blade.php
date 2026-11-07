@extends('frontend.layouts.app')
@section('content')
@php
    $breadcrumbs = [];
    if ($search ?? null) {
        $breadcrumbs[] = [
            'label' => 'Hasil Pencarian',
            'url' => null
        ];
    }
    else if (!$kategoriAktif){
        $breadcrumbs[] = [
            'label' => 'Semua Produk',
            'url' => null
        ];
    }
    if ($kategoriAktif){
        $kategoriBreadcrumb = $kategori->firstWhere(
            'id',
            $kategoriAktif
        );
        if ($kategoriBreadcrumb){
            $breadcrumbs[] = [
                'label' => $kategoriBreadcrumb->nama_kategori,
                'url' => route('produk.index', ['kategori' => $kategoriBreadcrumb->id])
            ];
        }
    }
    if ($subkategoriAktif && isset($kategoriBreadcrumb)){
        $subkategoriBreadcrumb = $kategoriBreadcrumb->subkategoris->firstWhere('id', $subkategoriAktif);
        if ($subkategoriBreadcrumb){
            $breadcrumbs[] = [
                'label' => $subkategoriBreadcrumb->nama_subkategori,
                'url' => null
            ];
        }
    }
@endphp
<section class="bg-white text-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-8 py-9">
        <div class="mb-8">
            @include('frontend.components.breadcrumb',['breadcrumbs' => $breadcrumbs])
        </div>
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-800">Semua Produk</h1>
        </div>
        @if ($kategori->count() && !($search ?? null))
        <div class="mb-10">
            <div class="flex items-center gap-3 overflow-x-auto pb-2">
                <a href="{{ route('produk.index') }}"
                class="flex-none px-5 py-2.5 rounded-full border text-sm font-semibold transition {{ !$kategoriAktif ? 'bg-[#0B6B35] text-white border-[#0B6B35]' : 'bg-[#F5F7F5] text-gray-700 border-gray-200 hover:bg-[#E8F5E9]' }}">
                    Semua
                </a>
                @foreach ($kategori as $item)
                    <a href="{{ route('produk.index', ['kategori' => $item->id]) }}" 
                    class="flex-none px-5 py-2.5 rounded-full border text-sm font-medium transition {{ $kategoriAktif == $item->id ? 'bg-[#0B6B35] text-white border-[#0B6B35]':'bg-[#F5F7F5] text-gray-700 border-gray-200 hover:bg-[#E8F5E9] hover:text-[#06351D]'}}">
                        {{ $item->nama_kategori }}
                    </a>
                @endforeach
            </div>
            @if ($kategoriAktif)
                @php
                    $kategoriTerpilih = $kategori->firstWhere('id', $kategoriAktif);
                @endphp
                @if ($kategoriTerpilih && $kategoriTerpilih->subkategoris->count())
                <div class="mt-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-gray-500 mb-3">{{ $kategoriTerpilih->nama_kategori }}</p>
                    <div class="flex items-center gap-2 overflow-x-auto pb-2">
                       <a href="{{ route('produk.index', ['kategori' => $kategoriTerpilih->id]) }}"
                        class="flex-none px-4 py-2 rounded-full border text-sm font-semibold transition {{ !$subkategoriAktif ? 'bg-[#0B6B35] text-white border-[#0B6B35]' : 'bg-[#F5F7F5] text-gray-700 border-gray-200 hover:bg-[#E8F5E9]' }}">
                            Semua
                        </a>
                        @foreach ($kategoriTerpilih->subkategoris as $subkategori)
                            <a href="{{ route('produk.index', ['kategori' => $kategoriTerpilih->id, 'subkategori' => $subkategori->id]) }}"
                                class="flex-none px-4 py-2 rounded-full border text-sm font-semibold transition {{ $subkategoriAktif == $subkategori->id ? 'bg-[#0B6B35] text-white border-[#0B6B35]' : 'bg-[#F5F7F5] text-gray-700 border-gray-200 hover:bg-[#E8F5E9] hover:text-[#06351D]' }}">
                                {{ $subkategori->nama_subkategori }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            @endif
        </div>
        @endif
        @if ($produk->count())
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                @foreach ($produk as $produkItem)
                    <div class="bg-white text-black rounded-xl overflow-hidden shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-xl flex flex-col h-full">
                        <a href="{{ route('produk.detail', $produkItem->slug) }}" class="block flex-1">
                            <div class="relative aspect-square bg-gray-100 overflow-hidden">
                                @if ($produkItem->foto_produk)
                                    <img src="{{ route('produk.foto', ['filename' => basename($produkItem->foto_produk)]) }}" alt="{{ $produkItem->nama_produk }}" 
                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">Tidak ada foto</div>
                                @endif
                            </div>
                            <div class="px-3 pt-3 pb-2">
                                <h2 class="text-sm font-medium leading-snug line-clamp-2 h-[40px] overflow-hidden" title="{{ $produkItem->nama_produk }}">
                                    {{ $produkItem->nama_produk }}
                                </h2>
                                <div class="mt-2 h-[48px] flex flex-col justify-start">
                                    <p class="text-base font-bold text-green-600 leading-5 whitespace-nowrap">
                                        Rp {{ number_format($produkItem->harga, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </a>
                        <div class="px-3 pb-3 mt-auto">
                            <button type="button" class="tombol-tambah block w-full bg-green-500 text-black py-2 rounded-lg text-sm font-semibold text-center hover:bg-gray-200 transition" 
                            data-produk-id="{{ $produkItem->id }}">
                                + Tambah
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($produk->hasPages())
                <div class="flex justify-center mt-12">
                    <nav class="inline-flex items-center gap-1 rounded-xl bg-black border border-gray-800 p-2">
                        @if ($produk->onFirstPage())
                            <span class="w-12 h-12 flex items-center justify-center rounded-xl bg-gray-900 text-gray-600 text-2xl">
                                ‹
                            </span>
                        @else
                            <a href="{{ $produk->previousPageUrl() }}" class="w-12 h-12 flex items-center justify-center rounded-xl bg-gray-100 text-gray-700 text-2xl hover:bg-white transition">
                                ‹
                            </a>
                        @endif
                        @foreach ($produk->onEachSide(2)->getUrlRange(1, $produk->lastPage()) as $page => $url)
                            @if ($page == $produk->currentPage())
                                <span class="w-12 h-12 flex items-center justify-center rounded-xl bg-gray-200 text-black font-medium">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="w-12 h-12 flex items-center justify-center rounded-xl text-gray-300 hover:bg-gray-900 hover:text-white transition">{{ $page }}</a>
                            @endif
                        @endforeach
                        @if ($produk->hasMorePages())
                            <a href="{{ $produk->nextPageUrl() }}" class="w-12 h-12 flex items-center justify-center rounded-xl bg-gray-100 text-gray-700 text-2xl hover:bg-white transition">›</a>
                        @else
                            <span class="w-12 h-12 flex items-center justify-center rounded-xl bg-gray-900 text-gray-600 text-2xl">›</span>
                        @endif
                    </nav>
                </div>
            @endif
        @else
            <div class="py-10 text-center">
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('storage/images/tanda_tanya.png') }}" alt="Tidak ditemukan" class="w-50 h-60 object-contain">
                </div>
                @if ($search ?? null)
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Produk tidak ditemukan</h2>
                    <p class="mt-3 text-gray-600 text-sm md:text-base">Kami tidak menemukan produk dengan kata pencarian
                        <span class="text-white font-semibold">"{{ $search }}"</span>
                    </p>
                    @if ($saranPencarian)
                        <p class="mt-3 text-gray-400 text-sm md:text-base">Mungkin yang Anda maksud:    
                            <a href="{{ route('produk.index', ['search' => $saranPencarian]) }}" 
                            class="text-green-500 font-semibold hover:text-green-400 hover:underline transition">
                                {{ $saranPencarian }}
                            </a>
                        </p>
                    @endif
                @else
                    <p class="text-gray-400">Belum ada produk</p>
                @endif
            </div>
        @endif
    </div>
</section>
@endsection