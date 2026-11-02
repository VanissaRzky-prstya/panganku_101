<?php

namespace App\Filament\Resources\LaporanPendapatanProduks\Pages;

use App\Filament\Resources\LaporanPendapatanProduks\LaporanPendapatanProdukResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;

class ListLaporanPendapatanProduks extends ListRecords
{
    protected static string $resource = LaporanPendapatanProdukResource::class;
    protected function getHeaderActions(): array{
        return [
            ActionGroup::make([
                Action::make('excel')->label('Excel')->icon('heroicon-o-table-cells')
                ->url(fn () =>route('export.pendapatan.excel', ['filters' => $this->tableFilters,])),
                Action::make('pdf')->label('PDF')->icon('heroicon-o-document')
                ->url(fn () => route('export.pendapatan.pdf', ['filters' => $this->tableFilters,])),
            ])
            ->label('Export')->button()->icon('heroicon-o-arrow-down-tray'),
        ];
    }
}
