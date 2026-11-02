<?php

namespace App\Filament\Resources\Kategoris\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class KategoriForm
{
    public static function configure(Schema $schema): Schema{
        return $schema->components([
            TextInput::make('nama_kategori')->label('Nama Kategori')->required()->live()
            ->afterStateUpdated(fn ($state, callable $set)=>$set('slug', Str::slug($state))),

            TextInput::make('slug')->required()->readOnly(),
            FileUpload::make('foto_kategori')->label('Foto Kategori')->image()->directory('kategori')->required(),
        ]);
    }
}
