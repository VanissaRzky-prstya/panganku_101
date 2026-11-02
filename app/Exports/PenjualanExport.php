<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PenjualanExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        return Order::with('user')->get()->map(function ($order){
            return [
                'Kode Pesanan' => $order->kode_pesanan,
                'Pelanggan' => $order->user->name ?? '-',
                'Total Bayar' => $order->total_bayar,
                'Status Pembayaran' => $order->status_pembayaran,
                'Status Pesanan' => $order->status_pesanan,
                'Tanggal' => $order->created_at->format('d-m-Y H:i'),
            ];
        });
    }
    public function headings(): array{
        return [
            'Kode Pesanan',
            'Pelanggan',
            'Total Bayar',
            'Status Pembayaran',
            'Status Pesanan',
            'Tanggal',
        ];
    }
}
