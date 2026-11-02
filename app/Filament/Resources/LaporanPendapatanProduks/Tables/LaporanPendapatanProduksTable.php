<?php

namespace App\Filament\Resources\LaporanPendapatanProduks\Tables;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Actions\Action;
use Filament\Tables\Enums\FiltersLayout;

class LaporanPendapatanProduksTable
{
    public static function configure(Table $table): Table{
        return $table->modifyQueryUsing(function (Builder $query){
            return $query->join(
                'orders',
                'orders.id',
                '=',
                'order_items.order_id'
            )
            ->where('orders.status_pembayaran', 'paid')
            ->selectRaw('
                order_items.produk_id,
                SUM(order_items.qty) as total_terjual,
                SUM(order_items.subtotal) as total_pendapatan
            ')
            ->groupBy('order_items.produk_id');
        })
        ->columns([
            TextColumn::make('produk.nama_produk')->label('Produk')->searchable(),
            TextColumn::make('produk.subkategori.nama_subkategori')->label('Kategori')->badge(),
            TextColumn::make('total_terjual')->label('Jumlah Terjual')->sortable(false),
            TextColumn::make('total_pendapatan')->label('Pendapatan')->money('IDR')->sortable(),
        ])
        ->filters([
            Filter::make('laporan')->label('Filter')->columns(1)->form([
                Radio::make('jenis')->label('Jenis Laporan')->options([
                    'terlaris' => 'Produk Terlaris',
                    'kurang_laris' => 'Produk Kurang Laris',
                ])
                ->live(),
                
                Radio::make('waktu')->label('Rentang Waktu')->options([
                    'hari_ini' => 'Hari Ini',
                    '7_hari' => '7 Hari Terakhir',
                    'bulan' => 'Pilih Bulan',
                    'tanggal' => 'Pilih Tanggal',
                ])
                ->live(),
                
                Select::make('bulan')->label('Bulan')->options([
                    1=>'Januari',
                    2=>'Februari',
                    3=>'Maret',
                    4=>'April',
                    5=>'Mei',
                    6=>'Juni',
                    7=>'Juli',
                    8=>'Agustus',
                    9=>'September',
                    10=>'Oktober',
                    11=>'November',
                    12=>'Desember',
                ])
                ->visible(fn ($get) => $get('waktu') === 'bulan'),
                
                Select::make('tahun')->label('Tahun')->options(collect(range(date('Y'), date('Y')-5))->mapWithKeys(fn($y)=>[$y=>$y]))
                ->visible(fn ($get) => $get('waktu') === 'bulan'),
                
                DatePicker::make('tanggal_awal')->label('Tanggal Awal')->visible(fn ($get) => $get('waktu') === 'tanggal'),
                DatePicker::make('tanggal_akhir')->label('Tanggal Akhir')->visible(fn ($get) => $get('waktu') === 'tanggal'),
            ])
            ->query(function (Builder $query, array $data){
                if (($data['jenis'] ?? null ) === 'terlaris'){$query->orderByDesc('total_terjual');}
                if (($data['jenis'] ?? null ) === 'kurang_laris'){$query->orderBy('total_terjual');}
                if (($data['waktu'] ?? null ) === 'hari_ini'){$query->whereDate('orders.created_at',today());}
                if (($data['waktu'] ?? null ) === '7_hari'){$query->where('orders.created_at','>=',now()->subDays(7));}
                if (($data['waktu'] ?? null ) === 'bulan' && !empty($data['bulan']) && !empty($data['tahun'])){
                    $query->whereMonth('orders.created_at',$data['bulan'])->whereYear('orders.created_at',$data['tahun']);
                }
                if (($data['waktu'] ?? null ) === 'tanggal' && !empty($data['tanggal_awal']) && !empty($data['tanggal_akhir'])){
                    $query->whereBetween('orders.created_at',[$data['tanggal_awal'],$data['tanggal_akhir'],]);
                }
                return $query;
            }),
        ])
        ->filtersLayout(FiltersLayout::Modal)
        ->deferFilters()
        ->filtersApplyAction(fn (Action $action) => $action->label('Simpan'))
        ->recordActions([
            //
        ])
        ->toolbarActions([ 
            //
        ]);
    }
}
