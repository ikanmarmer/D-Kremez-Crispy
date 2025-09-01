<?php

namespace App\Filament\Karyawan\Resources\RekapHarians\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RekapHarianForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_users')
                    ->required()
                    ->numeric(),
                DatePicker::make('tanggal_rekap')
                    ->required(),
                TextInput::make('jumlah_produk_terjual')
                    ->required()
                    ->numeric(),
                TextInput::make('total_omzet')
                    ->required()
                    ->numeric(),
                TextInput::make('stok_tersedia')
                    ->required(),
            ]);
    }
}
