<?php

namespace App\Filament\Resources\LaporanPendapatanProduks;

use App\Filament\Resources\LaporanPendapatanProduks\Pages\ListLaporanPendapatanProduks;
use App\Filament\Resources\LaporanPendapatanProduks\Schemas\LaporanPendapatanProdukForm;
use App\Filament\Resources\LaporanPendapatanProduks\Tables\LaporanPendapatanProduksTable;
use App\Models\LaporanPendapatanProduk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LaporanPendapatanProdukResource extends Resource
{
    protected static ?string $model = LaporanPendapatanProduk::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;
    protected static ?string $recordTitleAttribute = 'produk_id';
    protected static ?string $navigationLabel = 'Pendapatan per Produk';
    protected static ?string $modelLabel = 'Pendapatan per Produk';
    protected static ?string $pluralModelLabel = 'Pendapatan per Produk';
    protected static string|\UnitEnum|null $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 4;
    public static function form(Schema $schema): Schema{
        return LaporanPendapatanProdukForm::configure($schema);
    }
    public static function table(Table $table): Table{
        return LaporanPendapatanProduksTable::configure($table);
    }
    public static function getRelations(): array{
        return [
            //
        ];
    }
    public static function getPages(): array{
        return [
            'index' => ListLaporanPendapatanProduks::route('/'),
        ];
    }
}
