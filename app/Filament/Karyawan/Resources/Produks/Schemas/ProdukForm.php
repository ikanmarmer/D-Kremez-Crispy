<?php

namespace App\Filament\Karyawan\Resources\Produks\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProdukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),
                TextInput::make('kode_produk')
                    ->required(),
                TextInput::make('harga')
                    ->required()
                    ->numeric(),
                TextInput::make('satuan')
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
