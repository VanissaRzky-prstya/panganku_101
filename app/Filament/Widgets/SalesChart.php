<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class SalesChart extends ChartWidget
{
     protected int|string|array $columnSpan = 'full';
    protected ?string $heading = 'Grafik Penjualan';
    public ?string $filter = '7_hari';
    protected function getFilters(): ?array{
        return [
            '7_hari' => '7 Hari Terakhir',
            '30_hari' => '30 Hari Terakhir',
            '1_tahun' => '1 Tahun Terakhir',
        ]; 
    }
    protected function getData(): array{
        $labels = [];
        $data = [];
        if ($this->filter === '7_hari'){
            for ($i = 6; $i >= 0; $i--){
                $tanggal = Carbon::now()->subDays($i);
                $labels[] = $tanggal->translatedFormat('D');
                $data[] = Order::whereDate('paid_at', $tanggal)->where('status_pembayaran', 'paid')->sum('total_bayar');
            }
        } elseif ($this->filter === '30_hari'){
            for ($i = 29; $i >= 0; $i--){
                $tanggal = Carbon::now()->subDays($i);
                $labels[] = $tanggal->format('d M');
                $data[] = Order::whereDate('paid_at', $tanggal)->where('status_pembayaran', 'paid')->sum('total_bayar');
            }
        } else{
            for ($i = 11; $i >= 0; $i--){
                $bulan = Carbon::now()->subMonths($i);
                $labels[] = $bulan->translatedFormat('M');
                $data[] = Order::whereYear('paid_at', $bulan->year)->whereMonth('paid_at', $bulan->month)
                    ->where('status_pembayaran', 'paid')->sum('total_bayar');
            }
        }
        return[
            'datasets' => [
                ['label' => 'Pendapatan','data' => $data,'borderWidth' => 2,],
            ],
            'labels' => $labels,
        ];
    }
    protected function getType(): string{
        return 'bar';
    }
}
