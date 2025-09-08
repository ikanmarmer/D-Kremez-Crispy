<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Alamat email'),
                TextEntry::make('role')
                    ->label('Peran'),
                ImageEntry::make('avatar')
                    ->label('Avatar')
                    ->disk('public')
                    ->placeholder('Belum ada avatar')
                    ->circular()
                    ->height(100),
                TextEntry::make('created_at')
                    ->label('Dibuat pada')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('Diperbarui pada')
                    ->dateTime(),
            ]);
    }
}
