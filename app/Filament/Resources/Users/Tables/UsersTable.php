<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table{
        return $table->columns([
            ImageColumn::make('photo')
    ->label('Foto Profil')
    ->getStateUsing(fn ($record) => $record->photo
        ? asset('storage/' . $record->photo)
        : null)
    ->circular(),
            TextColumn::make('name')->searchable(),
            TextColumn::make('email')->label('Email address')->searchable(),
            TextColumn::make('address')->label('Alamat')->limit(40)->tooltip(fn ($record) => $record->address),
            TextColumn::make('phone')->label('Nomor HP')->searchable(),
            IconColumn::make('is_active')->boolean(),
            TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            //
        ]);
    }
}
