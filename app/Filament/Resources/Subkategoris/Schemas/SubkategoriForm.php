<?php

namespace App\Filament\Resources\Subkategoris\Schemas;

use App\Models\Kategori;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class SubkategoriForm
{
    public static function configure(Schema $schema): Schema{
        return $schema->components([
            Select::make('kategori_id')->label('Kategori')->options(Kategori::pluck('nama_kategori', 'id'))->searchable()->required(),
            TextInput::make('nama_subkategori')->label('Nama Sub Kategori')->required()->live()
                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
            TextInput::make('slug')->label('Slug')->readOnly()->required(),
        ]);
    }
}
