<?php

namespace App\Filament\Resources\OrderItems;

use App\Filament\Resources\OrderItems\Pages\CreateOrderItem;
use App\Filament\Resources\OrderItems\Pages\EditOrderItem;
use App\Filament\Resources\OrderItems\Pages\ListOrderItems;
use App\Filament\Resources\OrderItems\Schemas\OrderItemForm;
use App\Filament\Resources\OrderItems\Tables\OrderItemsTable;
use App\Models\OrderItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrderItemResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $model = OrderItem::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $recordTitleAttribute = 'id';
    protected static string|\UnitEnum|null $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Detail Pesanan';
    protected static ?string $modelLabel = 'Detail Pesanan';
    protected static ?string $pluralModelLabel = 'Detail Pesanan';
    public static function form(Schema $schema): Schema{
        return OrderItemForm::configure($schema);
    }
    public static function table(Table $table): Table{
        return OrderItemsTable::configure($table);
    }
    public static function getRelations(): array{
        return [
            //
        ];
    }
    public static function getPages(): array{
        return [
            'index' => ListOrderItems::route('/'),
            'create' => CreateOrderItem::route('/create'),
            'edit' => EditOrderItem::route('/{record}/edit'),
        ];
    }
}
