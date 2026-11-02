<?php

namespace App\Filament\Resources\Produks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Table;

class ProduksTable
{
    public static function configure(Table $table): Table{
        return $table->columns([
            ImageColumn::make('foto_produk')->label('Foto')->circular(),
            TextColumn::make('nama_produk')->label('Nama Produk')->searchable()->sortable(),
            TextColumn::make('subkategori.nama_subkategori')->label('Sub Kategori')->badge()->color('success')->searchable(),
            TextColumn::make('harga')->label('Harga')->money('IDR')->sortable(),
            TextColumn::make('stok')->label('Stok')->badge()->color(function ($state){
                if ($state == 0){
                    return 'danger';
                }
                if ($state <= 10){
                    return'warning';
                }
                return 'success';
            }),
            TextColumn::make('status_stok')->label('Status')->badge()->getStateUsing(fn ($record): string => match (true){
                $record->stok == 0 => 'Habis',
                $record->stok <= 22 => 'Hampir Habis',
                default => 'Aman',
            })->color(fn (string $state): string => match ($state){
                'Aman' => 'success',
                'Hampir Habis' => 'warning',
                'Habis' => 'danger',
                default => 'gray',
            }),
            TextColumn::make('berat')->label('Berat')->formatStateUsing(fn ($record) => "{$record->berat} {$record->satuan}"),
            TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')->label('Diubah')->dateTime('d M Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            //
        ])
        ->recordActions([
            EditAction::make(),
            Action::make('tambahStok')->label('Tambah Stok')->icon('heroicon-o-plus-circle')->color('success')->form([
                TextInput::make('jumlah')->label('Jumlah Stok')->numeric()->minValue(1)->required(),
            ])
            ->action(function (array $data, $record){
                $record->increment('stok', $data['jumlah']);
                Notification::make()->title('Stok berhasil ditambahkan')->success()->send();
            }),
        ])
        ->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
    }
}
