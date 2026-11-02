<?php

namespace App\Filament\Resources\Subkategoris\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SubkategorisTable
{
    public static function configure(Table $table): Table{
        return $table->columns([
            TextColumn::make('kategori.nama_kategori')->label('Kategori')->searchable()->sortable(),
            TextColumn::make('nama_subkategori')->label('Sub Kategori')->searchable()->sortable(),
            TextColumn::make('slug')->label('Slug')->copyable(),
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
