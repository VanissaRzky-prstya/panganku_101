<?php

namespace App\Filament\Resources\LaporanPenjualans\Pages;

use App\Filament\Resources\LaporanPenjualans\LaporanPenjualanResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Widgets\LaporanStats;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;

class ListLaporanPenjualans extends ListRecords
{
    protected static string $resource = LaporanPenjualanResource::class;
    protected function getHeaderActions(): array{
        return [
            ActionGroup::make([
                Action::make('excel')->label('Excel')->icon('heroicon-o-table-cells')->url(route('export.penjualan.excel')),
                Action::make('pdf')->label('PDF')->icon('heroicon-o-document')->url(route('export.penjualan.pdf')),
            ])
            ->label('Export')->button()->icon('heroicon-o-arrow-down-tray'),
        ];
    }
    protected function getHeaderWidgets(): array{
        return [
            LaporanStats::class,
        ];
    }
}
