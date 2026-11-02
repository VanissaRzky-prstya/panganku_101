<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table{
        return $table->columns([
            TextColumn::make('kode_pesanan')->label('Kode Pesanan')->searchable(),
            TextColumn::make('user.name')->label('Pelanggan')->searchable(),
            TextColumn::make('total_bayar')->label('Total Bayar')->money('IDR')->sortable(),
            TextColumn::make('status_pembayaran')->label('Status Pembayaran')->searchable(),
            TextColumn::make('status_pesanan')->label('Status Pesanan')->searchable(),
            TextColumn::make('tanggal_pengiriman')->label('Tanggal Pengiriman')->date()->sortable(),
            TextColumn::make('jam_pengiriman')->label('Jam Pengiriman'),
            TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')->label('Diubah')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
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
