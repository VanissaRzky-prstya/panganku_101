<?php

namespace App\Filament\Resources\Produks\Schemas;

use App\Models\Subkategori;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;

class ProdukForm
{
    public static function configure(Schema $schema): Schema{
        return $schema->components([
            Select::make('subkategori_id')->label('Sub Kategori')->relationship('subkategori', 'nama_subkategori')->searchable()
                ->preload()->required(),
            TextInput::make('nama_produk')->label('Nama Produk')->required()->live()->afterStateUpdated(function (Set $set, ?string $state){
                $set('slug', Str::slug($state));
            })
            ->maxLength(255),
            
            TextInput::make('slug')->label('Slug')->dehydrated()->required()->readOnly()->maxLength(255),
            Textarea::make('deskripsi')->label('Deskripsi')->rows(4),
            FileUpload::make('foto_produk')->label('Foto Produk')->image()->directory('produk')->required(),
            TextInput::make('harga')->numeric()->required(),
            TextInput::make('stok')->numeric()->required(),
            TextInput::make('berat')->numeric()->required(),
            Select::make('satuan')->options([
                'kg' => 'Kg',
                'gram' => 'Gram',
                'ml' => 'Ml',
                'l' => 'L',
                'pcs' => 'Pcs',
                'ikat' => 'Ikat',
            ])
            ->required(),
        ]);
    }
}
