<?php

namespace App\Filament\Karyawan\Resources\StokMentahs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StokMentahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('tanggal')
                    ->required(),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('harga')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0)
                    ->step(1)
                    ->default(0),
                TextInput::make('jumlah')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('harga_total_stok')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0)
                    ->step(1)
                    ->default(0),
            ]);
    }
}
