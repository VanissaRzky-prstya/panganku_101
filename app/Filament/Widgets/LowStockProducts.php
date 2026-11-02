<?php

namespace App\Filament\Widgets;

use App\Models\Produk;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LowStockProducts extends TableWidget
{
    public function getTableHeading(): string{
        return 'Stok Menipis';
    }
    protected function isTablePaginationEnabled(): bool{
        return true;
    }
    protected int|string|array $columnSpan = 'full';
    public function table(Table $table): Table{
        return $table->query(
            fn (): Builder => Produk::query()->where('stok', '<=', 22)->orderByRaw('CASE WHEN stok = 0 THEN 0 ELSE 1 END')->orderBy('stok', 'asc')
        )
        ->columns([
            TextColumn::make('nama_produk')->label('Nama Produk')->searchable(),
            TextColumn::make('subkategori.nama_subkategori')->label('Kategori')->badge()->color('success'),
            TextColumn::make('stok')->label('Jumlah Stok')->numeric()->badge()->color(fn ($state): string => (int) $state === 0 ? 'danger' : 'warning'),
            TextColumn::make('satuan')->label('Satuan'),
        ]);
    }
}
