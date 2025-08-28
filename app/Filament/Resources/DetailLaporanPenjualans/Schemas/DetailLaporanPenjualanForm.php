<?php

namespace App\Filament\Resources\DetailLaporanPenjualans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DetailLaporanPenjualanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_laporan')
                    ->required()
                    ->numeric(),
                TextInput::make('id_produk')
                    ->required()
                    ->numeric(),
                TextInput::make('jumlah_terjual')
                    ->required()
                    ->numeric(),
                TextInput::make('harga_satuan')
                    ->required()
                    ->numeric(),
                TextInput::make('total_harga')
                    ->required()
                    ->numeric(),
            ]);
    }
}
