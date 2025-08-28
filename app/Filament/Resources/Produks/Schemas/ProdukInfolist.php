<?php

namespace App\Filament\Resources\Produks\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProdukInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nama'),
                TextEntry::make('kode_produk'),
                TextEntry::make('harga')
                    ->numeric(),
                TextEntry::make('satuan'),
                IconEntry::make('aktif')
                    ->boolean(),
                TextEntry::make('dibuat_pada')
                    ->dateTime(),
                TextEntry::make('diperbarui_pada')
                    ->dateTime(),
            ]);
    }
}
