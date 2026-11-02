<?php

namespace App\Filament\Resources\Promos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PromosTable
{
    public static function configure(Table $table): Table{
        return $table->columns([
            TextColumn::make('produk.nama_produk')->label('Produk')->searchable()->sortable(),
            TextColumn::make('produk.harga')->label('Harga')->money('IDR'),
            TextColumn::make('tipe_potongan')->label('Jenis')->badge()
                ->color(fn ($state) => $state === 'persentase' ? 'success' : 'warning'),
            TextColumn::make('besar_potongan')->label('Diskon')->formatStateUsing(function ($record){
                return $record->tipe_potongan === 'persentase' ? 
                $record->besar_potongan . ' %' : 'Rp ' . number_format($record->besar_potongan, 0, ',', '.');
            }),
            TextColumn::make('tanggal_mulai')->label('Mulai')->date('d M Y'),
            TextColumn::make('tanggal_berakhir')->label('Berakhir')->date('d M Y'),
            IconColumn::make('is_active')->label('Status')->boolean(),
        ])
        ->filters([
            //
        ])
        ->recordActions([
            EditAction::make(),
        ])
        ->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
    }
}
