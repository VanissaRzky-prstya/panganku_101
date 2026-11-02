<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\OrderItem;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LaporanStats extends StatsOverviewWidget
{
    protected function getStats(): array{
        return[
            Stat::make(
                'Total Pendapatan',
                'Rp ' . number_format(Order::where('status_pembayaran', 'paid')->sum('total_bayar'), 0, ',', '.')
            )
            ->description('Pesanan yang sudah dibayar')->color('success'),
            Stat::make(
                'Jumlah Pesanan', Order::count()
            )
            ->description('Total seluruh pesanan')->color('primary'),
            Stat::make(
                'Produk Terjual', OrderItem::sum('qty')
            )
            ->description('Total item terjual')->color('warning'),
        ];
    }
}
