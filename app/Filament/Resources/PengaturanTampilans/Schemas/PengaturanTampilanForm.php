<?php

namespace App\Filament\Resources\PengaturanTampilans\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PengaturanTampilanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_tema')
                    ->default(null),
                TextInput::make('path_logo')
                    ->default(null),
                TextInput::make('warna_utama')
                    ->default(null),
                Textarea::make('latar')
                    ->default(null)
                    ->columnSpanFull(),
                Toggle::make('aktif')
                    ->required(),
                DateTimePicker::make('dibuat_pada')
                    ->required(),
                DateTimePicker::make('diperbarui_pada')
                    ->required(),
            ]);
    }
}
