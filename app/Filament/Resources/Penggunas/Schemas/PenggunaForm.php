<?php

namespace App\Filament\Resources\Penggunas\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PenggunaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('kata_sandi')
                    ->required(),
                Select::make('peran')
                    ->options([
            'pelanggan' => 'Pelanggan',
            'karyawan' => 'Karyawan',
            'admin' => 'Admin',
            'pemilik' => 'Pemilik',
        ])
                    ->required(),
                Toggle::make('aktif')
                    ->required(),
                DateTimePicker::make('dibuat_pada')
                    ->required(),
                DateTimePicker::make('diperbarui_pada')
                    ->required(),
            ]);
    }
}
