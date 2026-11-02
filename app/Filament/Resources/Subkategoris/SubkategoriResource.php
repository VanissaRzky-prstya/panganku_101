<?php

namespace App\Filament\Resources\Subkategoris;

use App\Filament\Resources\Subkategoris\Pages\CreateSubkategori;
use App\Filament\Resources\Subkategoris\Pages\EditSubkategori;
use App\Filament\Resources\Subkategoris\Pages\ListSubkategoris;
use App\Filament\Resources\Subkategoris\Schemas\SubkategoriForm;
use App\Filament\Resources\Subkategoris\Tables\SubkategorisTable;
use App\Models\Subkategori;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SubkategoriResource extends Resource
{
    protected static ?string $model = Subkategori::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;
    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';
    protected static ?string $recordTitleAttribute = 'nama_subkategori';
    protected static ?string $navigationLabel = 'Sub Kategori';
    protected static ?int $navigationSort = 3;
    public static function form(Schema $schema): Schema{
        return SubkategoriForm::configure($schema);
    }
    public static function table(Table $table): Table{
        return SubkategorisTable::configure($table);
    }
    public static function getRelations(): array{
        return [
            //
        ];
    }
    public static function getPages(): array{
        return [
            'index' => ListSubkategoris::route('/'),
            'create' => CreateSubkategori::route('/create'),
            'edit' => EditSubkategori::route('/{record}/edit'),
        ];
    }
}
