<?php

namespace App\Filament\Resources\LaporanPenjualans;

use App\Filament\Resources\LaporanPenjualans\Pages\ListLaporanPenjualans;
use App\Filament\Resources\LaporanPenjualans\Schemas\LaporanPenjualanForm;
use App\Filament\Resources\LaporanPenjualans\Tables\LaporanPenjualansTable;
use App\Models\Order;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LaporanPenjualanResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;
    protected static ?string $recordTitleAttribute = 'kode_pesanan';
    protected static ?string $navigationLabel = 'Penjualan Produk';
    protected static ?string $modelLabel = 'Penjualan Produk';
    protected static ?string $pluralModelLabel = 'Penjualan Produk';
    protected static string|\UnitEnum|null $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 1;
    public static function form(Schema $schema): Schema{
        return LaporanPenjualanForm::configure($schema);
    }
    public static function table(Table $table): Table{
        return LaporanPenjualansTable::configure($table);
    }
    public static function getRelations(): array{
        return [
            //
        ];
    }
    public static function getPages(): array{
        return [
            'index' => ListLaporanPenjualans::route('/'),
        ];
    }
}
