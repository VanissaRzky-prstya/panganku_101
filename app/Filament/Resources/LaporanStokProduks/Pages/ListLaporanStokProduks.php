<?php

namespace App\Filament\Resources\LaporanStokProduks\Pages;

use App\Filament\Resources\LaporanStokProduks\LaporanStokProdukResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;

class ListLaporanStokProduks extends ListRecords
{
    protected static string $resource = LaporanStokProdukResource::class;
    protected function getHeaderActions(): array{
        return [
            ActionGroup::make([
                Action::make('excel')
                    ->label('Excel')
                    ->icon('heroicon-o-table-cells')
                    ->url(fn () => route('export.stok.excel', [
                        'status' => $this->getTable()
                            ->getFilters()['status_stok']
                            ->getState()['value'] ?? null,
                    ])),

                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document')
                    ->url(fn () => route('export.stok.pdf', [
                        'status' => $this->getTable()
                            ->getFilters()['status_stok']
                            ->getState()['value'] ?? null,
                    ])),
            ])
                ->label('Export')
                ->button()
                ->icon('heroicon-o-arrow-down-tray'),
        ];
    }
}
