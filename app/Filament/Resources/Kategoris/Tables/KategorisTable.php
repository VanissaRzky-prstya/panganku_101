<?php

namespace App\Filament\Resources\Kategoris\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KategorisTable
{
    public static function configure(Table $table): Table{
        return $table->columns([
            ImageColumn::make('foto_kategori')->label('Foto')->square(),
            TextColumn::make('nama_kategori')->label('Nama Kategori')->searchable()->sortable(),
            TextColumn::make('slug')->label('Slug')->searchable()->copyable(),
            TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y H:i')->sortable(),
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
