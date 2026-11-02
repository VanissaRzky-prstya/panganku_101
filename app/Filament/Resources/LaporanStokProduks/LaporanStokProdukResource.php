<?php

namespace App\Filament\Resources\LaporanStokProduks;

use App\Filament\Resources\LaporanStokProduks\Pages\ListLaporanStokProduks;
use App\Filament\Resources\LaporanStokProduks\Schemas\LaporanStokProdukForm;
use App\Filament\Resources\LaporanStokProduks\Tables\LaporanStokProduksTable;
use App\Models\Produk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LaporanStokProdukResource extends Resource
{
    protected static ?string $model = Produk::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;
    protected static ?string $recordTitleAttribute = 'nama_produk';
    protected static ?string $navigationLabel = 'Stok Produk';
    protected static ?string $modelLabel = 'Stok Produk';
    protected static ?string $pluralModelLabel = 'Stok Produk';
    protected static string|\UnitEnum|null $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 6;
    public static function form(Schema $schema): Schema{
        return LaporanStokProdukForm::configure($schema);
    }
    public static function table(Table $table): Table{
        return LaporanStokProduksTable::configure($table);
    }
    public static function getRelations(): array{
        return [
            //
        ];
    }
    public static function getPages(): array{
        return [
            'index' => ListLaporanStokProduks::route('/'),
        ];
    }
}
