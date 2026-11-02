<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema{
        return $schema->components([
            Section::make('Informasi Pesanan')
                ->schema([
                    TextInput::make('kode_pesanan')
                        ->label('Kode Pesanan')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('total_harga')
                        ->label('Subtotal')
                        ->prefix('Rp')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('ongkir')
                        ->label('Ongkos Kirim')
                        ->prefix('Rp')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('total_bayar')
                        ->label('Total Pembayaran')
                        ->prefix('Rp')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('payment_method')
                        ->label('Metode Pembayaran')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('status_pembayaran')
                        ->label('Status Pembayaran')
                        ->disabled()
                        ->dehydrated(false),
                ])
                ->columns(2),

            Section::make('Pengelolaan Pesanan')
                ->schema([
                    Select::make('status_pesanan')
                        ->label('Status Pesanan')
                        ->options([
                            'pending' => 'Menunggu',
                            'processing' => 'Sedang Diproses',
                            'shipping' => 'Dikirim',
                            'completed' => 'Selesai',
                            'cancelled' => 'Batal',
                        ])
                        ->required(),

                    DatePicker::make('tanggal_pengiriman')
                        ->label('Tanggal Pengiriman')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('jam_pengiriman')
                        ->label('Jam Pengiriman')
                        ->disabled()
                        ->dehydrated(false),
                ])
                ->columns(2),
        ]);
    }
}
