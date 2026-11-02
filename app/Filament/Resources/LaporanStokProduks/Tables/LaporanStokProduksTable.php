<?php

namespace App\Filament\Resources\LaporanStokProduks\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Table;

class LaporanStokProduksTable
{
    public static function configure(Table $table): Table{
        return $table->columns([
            TextColumn::make('nama_produk')->label('Produk')->searchable(),
            TextColumn::make('subkategori.nama_subkategori')->label('Kategori')->badge(),
            TextColumn::make('jumlah_stok')->label('Jumlah Stok')->state(fn ($record) => $record->stok)->numeric(),
            TextColumn::make('satuan')->label('Satuan'),
            TextColumn::make('status_stok')->label('Status')->state(fn ($record) => $record->stok)->badge()->formatStateUsing(function ($state){
                if ($state == 0){
                    return 'Habis';
                }
                if ($state <= 22){
                    return 'Hampir Habis';
                }
                return 'Aman';
            })->color(function ($state){
                if ($state == 0){
                    return 'danger';
                }
                if ($state <= 22){
                    return 'warning';
                }
                return 'success';
            }),
        ])
        ->filters([
    SelectFilter::make('status_stok')
        ->label('Status Stok')
        ->options([
            'habis' => 'Habis',
            'hampir_habis' => 'Hampir Habis',
            'aman' => 'Aman',
        ])
        ->query(function (Builder $query, array $data): Builder {
            return match ($data['value'] ?? null) {
                'habis' => $query->where('stok', 0),
                'hampir_habis' => $query->where('stok', '>', 0)
                    ->where('stok', '<=', 22),
                'aman' => $query->where('stok', '>', 22),
                default => $query,
            };
        }),
])
        ->recordActions([
            ///
        ])
        ->toolbarActions([
            ///
        ]);
    }
}
