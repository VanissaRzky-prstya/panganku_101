<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema{
        return $schema->components([
            FileUpload::make('photo')->image()->directory('users')->label('foto-profil'),
            TextInput::make('name')->required(),
            TextInput::make('email')->label('Email')->email()->required(),
            TextInput::make('password')->password()->revealable()->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $operation) => $operation === 'create'),
            Textarea::make('address')->label('Alamat')->rows(3)->columnSpanFull(),
            TextInput::make('phone')->label('Nomor HP')->tel(),
            Toggle::make('is_active')->label('Status Aktif')->default(true),
        ]);
    }
}
