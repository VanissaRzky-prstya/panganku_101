<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Produk;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array{
        return[
            Stat::make('Total User', User::count())->description('Jumlah seluruh pengguna')->color('primary')->icon('heroicon-o-users'),
            Stat::make('Total Produk', Produk::count())->description('Jumlah seluruh produk')->color('success')->icon('heroicon-o-shopping-bag'),
            Stat::make('Pesanan Diproses',Order::where('status_pesanan', 'processing')->count())->description('Pesanan yang sedang diproses')
                ->color('warning')->icon('heroicon-o-shopping-cart'),
            Stat::make('Pendapatan Hari Ini','Rp ' . number_format(Order::whereDate('created_at', today())->where('status_pembayaran', 'paid')->sum('total_bayar'), 0, ',', '.'))
                ->description('Total pendapatan hari ini')->color('danger')->icon('heroicon-o-banknotes'),
        ];
    }
}
