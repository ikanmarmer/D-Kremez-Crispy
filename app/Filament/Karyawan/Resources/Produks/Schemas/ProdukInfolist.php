<?php

namespace App\Filament\Karyawan\Resources\Produks\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProdukInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nama'),
                TextEntry::make('harga')
                    ->numeric(),
                TextEntry::make('stok')
                    ->numeric(),
                ImageEntry::make('image')
                    ->placeholder('-'),
                TextEntry::make('dibuat_pada')
                    ->dateTime(),
                TextEntry::make('diperbarui_pada')
                    ->dateTime(),
                IconEntry::make('aktif')
                    ->boolean(),
            ]);
    }
}
