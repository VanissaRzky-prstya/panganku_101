<?php

namespace App\Filament\Resources\LaporanPenjualans\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class LaporanPenjualansTable
{
    public static function configure(Table $table): Table{
        return $table->columns([
            TextColumn::make('kode_pesanan')->label('Kode Pesanan')->searchable(),
            TextColumn::make('user.name')->label('Pelanggan')->searchable(),
            TextColumn::make('total_bayar')->label('Total Bayar')->money('IDR')->sortable(),
            TextColumn::make('status_pembayaran')->label('Pembayaran')->badge(),
            TextColumn::make('status_pesanan')->label('Pesanan')->badge(),
            TextColumn::make('created_at')->label('Tanggal')->dateTime('d M Y H:i')->sortable(),
        ])
        ->filters([
            SelectFilter::make('periode')->label('Periode')->options([
                'hari_ini' => 'Hari Ini',
                '7_hari' => '7 Hari Terakhir',
                '30_hari' => '30 Hari Terakhir',
                '1_tahun' => '1 Tahun Terakhir',
            ])
            ->query(function (Builder $query, array $data): Builder{
                return match ($data['value'] ?? null){
                    'hari_ini' => $query->whereDate('created_at', today()),
                    '7_hari' => $query->whereDate(
                        'created_at',
                        '>=',
                        now()->subDays(6)
                    ),
                    '30_hari' => $query->whereDate(
                        'created_at',
                        '>=',
                        now()->subDays(29)
                    ),
                    '1_tahun' => $query->whereDate(
                        'created_at',
                        '>=',
                        now()->subYear()
                    ),
                    default => $query,
                };
            }),
        ])
        ->recordActions([])->toolbarActions([]);
    }
}
