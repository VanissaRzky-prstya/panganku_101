<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StokProdukExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected ?string $status;
    public function __construct(?string $status = null){
        $this->status = $status;
    }
    public function collection(){
        $query = Produk::with('subkategori');

        if ($this->status === 'habis') {
            $query->where('stok', 0);
        } elseif ($this->status === 'hampir_habis') {
            $query->where('stok', '>', 0)
                  ->where('stok', '<=', 22);
        } elseif ($this->status === 'aman') {
            $query->where('stok', '>', 22);
        }
        return $query->get()->map(function ($produk) {
            if ($produk->stok == 0) {
                $status = 'Habis';
            } elseif ($produk->stok <= 22) {
                $status = 'Hampir Habis';
            } else {
                $status = 'Aman';
            }

            return [
                'Nama Produk' => $produk->nama_produk,
                'Kategori' => $produk->subkategori->nama_subkategori ?? '-',
                'Harga' => $produk->harga,
                'Stok' => $produk->stok,
                'Status' => $status,
            ];
        });
    }
    public function headings(): array{
        return [
            'Nama Produk',
            'Kategori',
            'Harga',
            'Stok',
            'Status',
        ];
    }
}
