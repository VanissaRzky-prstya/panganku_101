@extends('frontend.layouts.app')
@section('content')
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-8 py-9">
        <div class="mb-8">
            <p class="text-xs md:text-sm uppercase tracking-[0.25em] text-gray-500">Jelajahi</p>
            <h2 class="text-black text-2xl md:text-3xl font-bold mt-2">Kategori Produk</h2>
        </div>
        @php
            $urutanKategori = [
                'Sayur',
                'Buah',
                'Makanan',
                'Minuman',
                'Kebutuhan Dapur',
                'Makanan Beku',
            ];
            $kategoriTersusun = $kategori->sortBy(function ($item) use ($urutanKategori){
                $posisi = array_search($item->nama_kategori, $urutanKategori);
                return $posisi === false ? 999 : $posisi;
            });
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-5">
            @foreach ($kategoriTersusun as $item)
            <a href="{{ route('produk.index', ['kategori' => $item->id]) }}" class="group text-center">
                <div class="bg-white rounded-2xl overflow-hidden shadow-md border border-gray-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="px-2 py-3.5 text-center bg-white">
                        <h3 class="text-xs md:text-base font-semibold text-gray-800 group-hover:text-green-600 transition">{{ $item->nama_kategori }}</h3>
                    </div>
                    <div class="relative w-full aspect-[1.5/1] overflow-hidden bg-gray-100">
                        @if ($item->foto_kategori)
                            <img src="{{ route('kategori.foto', ['filename' => basename($item->foto_kategori)]) }}" alt="{{ $item->nama_kategori }}" 
                            class="w-full h-full object-cover opacity-80 transition-all duration-500 group-hover:scale-105 group-hover:opacity-90">
                            <div class="absolute inset-0 bg-white/10 pointer-events-none"></div>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-600 text-sm">Tidak ada foto</div>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
<section class="bg-orange-500 text-white">
    <div class="max-w-7xl mx-auto px-8 py-9">
        @if ($promo->count())
            <div class="grid lg:grid-cols-[260px_1fr] gap-8 items-stretch">
                <div class="flex flex-col justify-center">
                    <p class="text-xs uppercase tracking-[0.3em] text-white/70 font-semibold mb-4">Special Diskon</p>
                    <h2 class="text-3xl md:text-4xl font-black leading-[0.9] italic">PROMO</h2>
                    <h2 class="text-2xl md:text-3xl font-black leading-tight italic mt-1">MINGGU INI</h2>
                    <div class="flex items-center gap-2 mt-5">
                        <span class="w-16 h-2 rounded-full bg-gray-200"></span>
                        <span class="w-10 h-2 rounded-full bg-green-500"></span>
                    </div>
                    <p class="mt-5 text-sm md:text-base text-white/85 leading-relaxed max-w-[220px]">
                        Temukan berbagai produk pilihan dengan harga spesial minggu ini
                    </p>
                    <div class="mt-5">
                        <a href="{{ route('promo.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white/80 border border-white text-green-600 font-semibold text-sm hover:bg-white transition">
                            Lihat Semua<span>→</span>
                        </a>
                    </div>
                </div>
                <div class="relative min-w-0">
                    <div id="promoCarousel" class="overflow-hidden w-full rounded-2xl">
                        <div id="promoTrack" class="flex gap-4 transition-transform duration-500 ease-out">
                            @foreach ($promo as $item)
                                @php
                                $produkPromo = $item->produk;
                                if (!$produkPromo){
                                    continue;
                                }   
                                if ($item->tipe_potongan === 'persentase'){
                                    $hargaPromo = $produkPromo->harga - ($produkPromo->harga * $item->besar_potongan / 100);
                                } else {
                                    $hargaPromo = $produkPromo->harga -
                                    $item->besar_potongan;
                                }
                                $hargaPromo = max(0, $hargaPromo);
                                @endphp
                                <div href="{{ route('produk.detail', $produkPromo->slug) }}" class="promo-card flex-none w-[180px] sm:w-[190px] md:w-[195px] bg-white text-black rounded-xl overflow-hidden shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-xl flex flex-col">
                                    <a href="{{ route('produk.detail', $produkPromo->slug) }}"class="block flex-1">
                                        <div class="relative aspect-square bg-gray-100 overflow-hidden">
                                            @if ($produkPromo->foto_produk)
                                                <img src="{{ route('produk.foto', ['filename' => basename($produkPromo->foto_produk)])}}" alt="{{ $produkPromo->nama_produk }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                            @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                                                Tidak ada foto
                                            </div>
                                            @endif
                                            <div class="absolute top-2 left-2 z-10 bg-green-500 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-md whitespace-nowrap">
                                                @if ($item->tipe_potongan === 'persentase')
                                                    {{ number_format($item->besar_potongan, 0) }}%
                                                @else
                                                    HEMAT Rp {{ number_format($item->besar_potongan, 0, ',', '.') }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="px-3 pt-3 pb-2">
                                            <h3 class="text-sm font-medium leading-snug line-clamp-2 h-[40px] overflow-hidden" title="{{ $produkPromo->nama_produk }}">
                                                {{ $produkPromo->nama_produk }}
                                            </h3>
                                            <div class="mt-2 h-[48px] flex flex-col justify-start">
                                                <p class="text-base font-bold text-green-600 leading-5 whitespace-nowrap">
                                                    Rp {{ number_format($hargaPromo, 0, ',', '.') }}
                                                </p>
                                                <p class="mt-0.5 text-xs text-gray-400 line-through leading-4 whitespace-nowrap">
                                                    Rp {{ number_format($produkPromo->harga, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="px-3 pb-3 mt-auto">
                                        <button type="button" class="tombol-tambah block w-full bg-green-500 text-black py-2 rounded-lg text-sm font-semibold text-center hover:bg-gray-200 transition"
                                        data-produk-id="{{ $produkPromo->id }}"
                                        @guest
                                            onclick="window.location.href='{{ route('masuk') }}'"
                                        @endguest
                                        >
                                            + Tambah
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button id="promoPrev" type="button" class="hidden absolute left-0 top-1/2 -translate-y-1/2 -translate-x-3 w-10 h-10 rounded-full bg-white text-black shadow-lg items-center justify-center text-xl font-bold hover:bg-gray-100 transition z-20">
                        ‹
                    </button>
                    <button id="promoNext" type="button" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-3 w-10 h-10 rounded-full bg-white text-black shadow-lg flex items-center justify-center text-xl font-bold hover:bg-gray-100 transition z-20">
                        ›
                    </button>
                </div>
            </div>
        @else
            <div class="py-12 text-center">
                <p class="text-white/70">
                    Belum ada promo aktif
                </p>
            </div>
        @endif
    </div>
</section>
<section class="bg-white text-white">
    <div class="max-w-7xl mx-auto px-8 py-9">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs md:text-sm uppercase tracking-[0.25em] text-gray-500">Pilihan</p>
                <h2 class="text-black text-2xl md:text-3xl font-bold mt-2">Produk Pilihan</h2>
            </div>
        </div>
        @if ($produk->count())
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                @foreach ($produk->take(18) as $produkItem)
                    @php
                        $promoProduk = $produkItem->promoAktif;
                        $hargaAwal = $produkItem->harga;
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
                    <div class="bg-white text-black rounded-xl overflow-hidden shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-xl flex flex-col h-full">
                        <a href="{{ route('produk.detail', $produkItem->slug) }}" class="block flex-1">
                            <div class=" relative aspect-square bg-gray-100 overflow-hidden">
                                @if ($produkItem->foto_produk)
                                    <img src="{{ route('produk.foto', ['filename' => basename($produkItem->foto_produk)]) }}" alt="{{ $produkItem->nama_produk }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                                        Tidak ada foto
                                    </div>
                                @endif
                                @if ($promoProduk)
                                    @php
                                        if ($promoProduk->tipe_potongan === 'persentase'){
                                            $persentasePromo = $promoProduk->besar_potongan;
                                        }else{
                                            $persentasePromo = $hargaAwal > 0 ? round(($promoProduk->besar_potongan / $hargaAwal) * 100): 0;
                                        }
                                    @endphp
                                    <div class="absolute top-2 left-2 z-10 bg-green-500 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-md whitespace-nowrap">
                                        {{ number_format($persentasePromo, 0) }}% 
                                    </div>
                                @endif
                            </div>
                            <div class="px-3 pt-3 pb-2">
                                <h3 class="text-sm font-medium leading-snug line-clamp-2 h-[40px] overflow-hidden" title="{{ $produkItem->nama_produk }}">
                                    {{ $produkItem->nama_produk }}
                                </h3>
                                <div class="mt-2 h-[48px] flex flex-col justify-start">
                                    @if ($promoProduk)
                                        <p class="text-base font-bold text-green-600 leading-5 whitespace-nowrap">
                                            Rp {{ number_format($hargaPromo, 0, ',', '.') }}
                                        </p>
                                        <p class="mt-0.5 text-xs text-gray-400 line-through leading-4 whitespace-nowrap">
                                            Rp {{ number_format($hargaAwal, 0, ',', '.') }}
                                        </p>
                                    @else
                                        <p class="text-base font-bold text-green-600 leading-5 whitespace-nowrap">
                                            Rp {{ number_format($hargaAwal, 0, ',', '.') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </a> 
                        <div class="px-3 pb-3 mt-auto">
                            <button type="button" class="tombol-tambah block w-full bg-green-500 text-black py-2 rounded-lg text-sm font-semibold text-center hover:bg-gray-200 transition"
                            data-produk-id="{{ $produkItem->id }}"
                            @guest
                                onclick="window.location.href='{{ route('masuk') }}'"
                            @endguest
                            >
                                + Tambah
                            </button>
                        </div>
                    </div>       
                @endforeach
                <div class="col-span-full flex justify-center mt-2">
                    <a href="{{ route('produk.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white/80 border border-white text-green-600 font-semibold text-sm hover:bg-white transition">
                        Lihat Semua 
                        <span>→</span>
                    </a>
                </div>
            </div>
        @else
            <div class="py-12 text-center">
                <p class="text-gray-400">Belum ada produk</p>
            </div>
        @endif
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const track = document.getElementById('promoTrack');
    const carousel = document.getElementById('promoCarousel');
    const prevButton = document.getElementById('promoPrev');
    const nextButton = document.getElementById('promoNext');
    if (!track || !carousel || !prevButton || !nextButton) {
        return;
    }
    const cards = track.querySelectorAll('.promo-card');

    if (cards.length === 0) {
        return;
    }
    let currentPosition = 0;

    function getStep() {
        const card = cards[0];
        const cardWidth = card.offsetWidth;
        const style = window.getComputedStyle(track);
        const gap = parseFloat(style.columnGap || style.gap || 0);
        return cardWidth + gap;
    }

    function getMaxPosition() {
        const totalWidth = track.scrollWidth;
        const visibleWidth = carousel.clientWidth;
        return Math.max(0, totalWidth - visibleWidth);
    }

    function updateCarousel() {
        const maxPosition = getMaxPosition();
        if (currentPosition <= 0) {
            currentPosition = 0;
            prevButton.classList.remove('flex');
            prevButton.classList.add('hidden');
        } else {
            prevButton.classList.remove('hidden');
            prevButton.classList.add('flex');
        }

        if (currentPosition >= maxPosition) {
            currentPosition = maxPosition;
            nextButton.classList.remove('flex');
            nextButton.classList.add('hidden');
        } else {
            nextButton.classList.remove('hidden');
            nextButton.classList.add('flex');
        }

        track.style.transform =
            `translateX(-${currentPosition}px)`;
    }

    nextButton.addEventListener('click', function () {
        const step = getStep();
        const maxPosition = getMaxPosition();
        currentPosition += step;
        if (currentPosition > maxPosition) {
            currentPosition = maxPosition;
        }
        updateCarousel();
    });

    prevButton.addEventListener('click', function () {
        const step = getStep();
        currentPosition -= step;
        if (currentPosition < 0) {
            currentPosition = 0;
        }
        updateCarousel();
    });

    window.addEventListener('resize', function () {
        const maxPosition = getMaxPosition();
        if (currentPosition > maxPosition) {
            currentPosition = maxPosition;
        }
        updateCarousel();
    });
    updateCarousel();
});

function bukaModalKategori(){
    const modal = document.getElementById('modalKategori');
    if (!modal){
        return;
    }
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function tutupModalKategori(){
    const modal = document.getElementById('modalKategori');
    if (!modal) {
        return;
    }
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}
document.addEventListener('keydown', function(event){
    if (event.key === 'Escape'){
        tutupModalKategori();
    }
});
</script>
@endsection