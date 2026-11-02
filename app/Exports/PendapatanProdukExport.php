<?php

namespace App\Exports;

use App\Models\OrderItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Database\Eloquent\Builder;

class PendapatanProdukExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected array $filters;
    public function __construct(array $filters = []){
        $this->filters = $filters;
    }
    public function collection(){
        $data = $this->filters;
        $jenis = $data['jenis'] ?? [];
        $pendapatan = $data['pendapatan'] ?? [];
        $waktu = $data['waktu'] ?? [];
        $query = OrderItem::with('produk.subkategori')->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('order_items.produk_id, SUM(order_items.qty) as total_terjual, SUM(order_items.subtotal) as total_pendapatan')
            ->groupBy('order_items.produk_id');

        if (in_array('terlaris', $jenis)){
            $query->orderByDesc('total_terjual');
        }
        if (in_array('kurang_laris', $jenis)){
            $query->orderBy('total_terjual');
        }
        if (in_array('max', $pendapatan)){
            $query->orderByDesc('total_pendapatan');
        }
        if (in_array('min', $pendapatan)){
            $query->orderBy('total_pendapatan');
        }
        if (in_array('hari_ini', $waktu)){
            $query->whereDate('orders.created_at', today());
        }
        if (in_array('7_hari', $waktu)){
            $query->whereDate('orders.created_at','>=',now()->subDays(7));
        }
        if ( in_array('bulan', $waktu) && !empty($data['bulan']) && !empty($data['tahun'])){
            $query->whereMonth('orders.created_at', $data['bulan'])
            ->whereYear('orders.created_at', $data['tahun']);
        }
        if (in_array('tanggal', $waktu) && !empty($data['tanggal_awal']) && !empty($data['tanggal_akhir'])){
            $query->whereBetween('orders.created_at', [
                $data['tanggal_awal'] . ' 00:00:00',
                $data['tanggal_akhir'] . ' 23:59:59',
            ]);
        }

        return $query->get()->map(function ($item){
            return[
                'Produk' => $item->produk->nama_produk ?? '-',
                'Kategori' => $item->produk->subkategori->nama_subkategori ?? '-',
                'Jumlah Terjual' => $item->total_terjual,
                'Pendapatan' => $item->total_pendapatan,
            ];
        });
    }
    public function headings(): array{
        return [
            'Produk',
            'Kategori',
            'Jumlah Terjual',
            'Pendapatan',
        ];
    }
}
