<?php

namespace App\Filament\Resources\Promos\Schemas;

use App\Models\Produk;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PromoForm
{
    public static function configure(Schema $schema): Schema{
        return $schema->components([
            Select::make('produk_id')->label('Produk')->relationship('produk', 'nama_produk')->searchable()->preload()->required(),
            Select::make('tipe_potongan')->label('Jenis Diskon')->options([
                'persentase' => 'Persentase (%)',
                'nominal' => 'Nominal (Rp)',
            ])
            ->default('persentase')->required(),
            TextInput::make('besar_potongan')->label('Nilai Diskon')->numeric()->required(),
            Placeholder::make('info')->label('Keterangan')->content(function (Get $get){
                $produk = Produk::find($get('produk_id'));
                if (! $produk){
                    return 'Pilih produk terlebih dahulu.';
                }
                return 'Harga produk saat ini : Rp ' . number_format($produk->harga, 0, ',', '.');
            }),
            DatePicker::make('tanggal_mulai')->label('Tanggal Mulai')->required(),
            DatePicker::make('tanggal_berakhir')->label('Tanggal Berakhir')->required(),
            Toggle::make('is_active')->label('Promo Aktif')->default(true),
        ])
        ->columns(2);
    }
}
