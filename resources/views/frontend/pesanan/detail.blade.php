@extends('frontend.layouts.app')
@section('title', 'Detail Pesanan - Panganku')
@section('content')
<section class="min-h-screen bg-gray-50 text-gray-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('pesanan.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 hover:bg-gray-100">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold">Detail Pesanan</h1>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-5">
            <div class="flex flex-wrap justify-between items-start gap-3">
                <div>
                    <p class="text-sm text-gray-500">Kode Pesanan</p>
                    <h2 class="text-lg font-bold text-green-700 break-all">{{ $pesanan->kode_pesanan }}</h2>
                </div>
                @php
                    $status = strtolower($pesanan->status_pesanan ?? 'pending');
                    $warnaStatus = match ($status){
                        'selesai', 'completed' => 'background-color: #CCFBF1; color: #0F766E;',
                        'dibatalkan', 'cancelled', 'canceled' => 'background-color: #FEE2E2; color: #B91C1C;',
                        'diproses', 'processing' => 'background-color: #DBEAFE; color: #1D4ED8;',
                        default => 'background-color: #FEF3C7; color: #B45309;',
                    };
                    $labelStatus = match ($status){
                        'processing' => 'Diproses',
                        'pending' => 'Menunggu',
                        'paid' => 'Dibayar',
                        'selesai', 'completed' => 'Selesai',
                        'dibatalkan', 'cancelled', 'canceled' => 'Dibatalkan',
                        default => ucfirst(str_replace('_', ' ', $status)),
                    };
                @endphp
                <span class="px-3 py-1.5 rounded-full text-xs font-semibold" style="{{ $warnaStatus }}">{{ $labelStatus }}</span>
            </div>
            <div class="border-t border-gray-100 mt-4 pt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Tanggal Pembelian</p>
                    <p class="text-sm font-medium">{{ $pesanan->created_at?->format('d M Y, H:i') ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Nama Pemesan</p>
                    <p class="text-sm font-medium">{{ $pesanan->user->name ?? 'Pengguna' }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-green-600">location_on</span>
                <h2 class="font-bold">Alamat Tujuan</h2>
            </div>
            @if ($pesanan->alamat)
                <p class="font-semibold text-sm">{{ $pesanan->alamat->nama_penerima }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $pesanan->alamat->nomor_telepon }}</p>
                <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                    {{ $pesanan->alamat->alamat_lengkap }}, Kel. {{ $pesanan->alamat->kelurahan_desa }},
                    Kec. {{ $pesanan->alamat->kecamatan }}, {{ $pesanan->alamat->kabupaten_kota }},
                    {{ $pesanan->alamat->provinsi }}
                </p>
                @if ($pesanan->alamat->patokan)
                    <p class="text-sm text-gray-500 mt-2">
                        <span class="font-medium">Patokan:</span>
                        {{ $pesanan->alamat->patokan }}
                    </p>
                @endif
            @else
                <p class="text-sm text-gray-500">Alamat tujuan tidak tersedia</p>
            @endif
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-5">
            <div class="flex items-center gap-2 mb-5">
                <span class="material-symbols-outlined text-green-600">shopping_basket</span>
                <h2 class="font-bold">Produk yang Dipesan</h2>
                <span class="text-xs text-gray-500">({{ $pesanan->items->sum('qty') }} barang)</span>
            </div>
            <div class="space-y-5">
                @foreach ($pesanan->items as $item)
                    <div class="flex gap-3 sm:gap-4">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 shrink-0 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                            @if ($item->produk && $item->produk->foto_produk)
                                <img src="{{ route('produk.foto', ['filename' => basename($item->produk->foto_produk)]) }}" alt="{{ $item->produk->nama_produk }}" 
                                class="w-full h-full object-cover"
                                >
                            @else
                                <span class="material-symbols-outlined text-gray-400" style="font-size: 32px">inventory_2</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-semibold text-gray-800">{{ $item->produk->nama_produk ?? 'Produk' }}</h3>
                            <p class="text-xs text-gray-500 mt-2">
                                {{ $item->qty }} × Rp {{ number_format($item->harga_setelah_diskon ?? $item->harga_asli ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-xs text-gray-500">Subtotal</p>
                            <p class="text-sm font-bold text-gray-800 mt-1">Rp {{ number_format($item->subtotal ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-green-600">payments</span>
                <h2 class="font-bold">Ringkasan Pembayaran</h2>
            </div>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center gap-3">
                    <span class="text-gray-500">Metode Pembayaran</span>
                    @php
                        $metode = strtolower($pesanan->payment_method ?? $pesanan->payment_type ?? '');
                    @endphp
                    @if ($metode)
                        <img src="{{ asset('images/pembayaran/' . $metode . '.jpg') }}" alt="{{ strtoupper($metode) }}" 
                        class="h-10 w-auto max-w-[120px] object-contain"
                        >
                    @else
                        <span class="font-medium text-gray-500">-</span>
                    @endif
                </div>
                <div class="flex justify-between gap-3">
                    <span class="text-gray-500">Subtotal Produk</span>
                    <span class="font-medium">Rp {{ number_format($pesanan->total_harga ?? 0, 0, ',', '.') }}</span>
                </div>
                @if (($pesanan->ongkir ?? 0) > 0)
                    <div class="flex justify-between gap-3">
                        <span class="text-gray-500">Ongkos Kirim</span>
                        <span class="font-medium">Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="border-t border-gray-100 pt-4 mt-2 flex justify-between items-center gap-3">
                    <span class="font-bold">Total Pembayaran</span>
                    <span class="text-xl font-bold text-green-600 text-right">Rp {{ number_format($pesanan->total_bayar ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between gap-3 pt-2">
                    <span class="text-gray-500">Status Pembayaran</span>
                    <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $pesanan->status_pembayaran ?? '-')) }}</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
