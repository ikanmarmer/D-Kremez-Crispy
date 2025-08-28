<?php

namespace App\Filament\Resources\PengaturanTampilans\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PengaturanTampilanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nama_tema'),
                TextEntry::make('path_logo'),
                TextEntry::make('warna_utama'),
                IconEntry::make('aktif')
                    ->boolean(),
                TextEntry::make('dibuat_pada')
                    ->dateTime(),
                TextEntry::make('diperbarui_pada')
                    ->dateTime(),
            ]);
    }
}
