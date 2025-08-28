<?php

namespace App\Filament\Resources\Testimonis\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TestimoniInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_pelanggan')
                    ->numeric(),
                TextEntry::make('penilaian')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('dimoderasi_oleh')
                    ->numeric(),
                TextEntry::make('dimoderasi_pada')
                    ->dateTime(),
                TextEntry::make('dibuat_pada')
                    ->dateTime(),
                TextEntry::make('diperbarui_pada')
                    ->dateTime(),
            ]);
    }
}
