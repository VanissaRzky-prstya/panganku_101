<?php

namespace App\Exports;

use App\Models\OrderItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProdukKurangLarisExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        return OrderItem::with('produk.subkategori')->selectRaw('produk_id, SUM(qty) as total_terjual, SUM(subtotal) as total_pendapatan')
            ->groupBy('produk_id')->orderBy('total_terjual')->get()->map(function ($item){
                return [
                    'Produk' => $item->produk->nama_produk ?? '-',
                    'Kategori' => $item->produk->subkategori->nama_subkategori ?? '-',
                    'Jumlah Terjual' => $item->total_terjual,
                    'Pendapatan' => $item->total_pendapatan,
                ];
            });
    }
    public function headings(): array{
        return[
            'Produk',
            'Kategori',
            'Jumlah Terjual',
            'Pendapatan',
        ];
    }
}
