@extends('frontend.layouts.app')
@section('title', 'Pesanan Saya - Panganku')
@section('content')
<section class="min-h-[60vh] bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4">
        <div class="mb-4">
            @include('frontend.components.breadcrumb',[
                'breadcrumbs' => [
                    [
                        'label' => 'Pesanan',
                        'url' => route('pesanan.index')
                    ]
                ]
            ])
        </div>
    </div>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Pesanan</h1>
            <p class="text-sm text-gray-500 mt-1">Lihat pesanan dan riwayat belanja</p>
        </div>
        @if ($pesanan->isEmpty())
            <div class="bg-white border border-gray-200 rounded-xl px-5 py-14 text-center shadow-sm">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-green-50 flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-600"style="font-size: 44px">receipt_long</span>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Belum ada pesanan saat ini</h2>
                <p class="text-sm text-gray-500 mt-2 mb-5">Duh pesananmu belom ada nih, belanja dulu yuk</p>
                <a href="{{ route('produk.index') }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($pesanan as $order)
                    @php
                        $jumlahProduk = $order->items->sum('qty');
                        $namaProduk = $order->items->first()?->produk?->nama_produk ?? 'Pesanan PANGANKU';
                        $status = strtolower($order->status_pesanan ?? 'pending');
                        $warnaStatus = match ($status){
                            'selesai', 'completed' => 'bg-teal-100 text-teal-700',
                            'dibatalkan', 'cancelled', 'canceled' => 'bg-red-100 text-red-700',
                            'diproses', 'processing' => 'bg-blue-100 text-blue-700',
                            default => 'bg-amber-100 text-amber-700',
                        };
                    @endphp
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-4 pt-3 flex flex-wrap items-center gap-2">
                            <span class="material-symbols-outlined text-amber-500" style="font-size: 20px">shopping_bag</span>
                            <span class="text-xs text-gray-500">{{ $order->created_at?->format('d M Y · H:i') }}</span>
                            <span class="text-xs px-3 py-1 rounded-full font-semibold {{ $warnaStatus }}">
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </span>
                        </div>
                        <div class="px-4 py-4 flex items-center gap-3">
                            <div class="w-12 h-12 shrink-0 rounded-lg bg-green-50 flex items-center justify-center">
                                <span class="material-symbols-outlined text-green-700" style="font-size: 27px">shopping_basket</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h2 class="text-sm font-semibold text-gray-800 truncate">{{ $order->user->name ?? 'Pengguna' }}</h2>
                                <p class="text-xs text-gray-500 mt-1 break-all">Kode: {{ $order->kode_pesanan }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-xs text-gray-500">Total Belanja</p>
                                <p class="text-xs text-gray-700 mt-0.5">{{ $jumlahProduk }} Barang</p>
                                <p class="text-sm font-bold text-green-600 mt-0.5">Rp {{ number_format($order->total_bayar ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="border-t border-gray-100 px-4 py-3 flex justify-end">
                            <a href="{{ route('pesanan.detail', $order->id) }}" 
                            class="inline-flex items-center gap-1.5 border border-green-400 text-green-600 hover:bg-green-50 px-4 py-2 rounded-lg text-xs font-medium transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
