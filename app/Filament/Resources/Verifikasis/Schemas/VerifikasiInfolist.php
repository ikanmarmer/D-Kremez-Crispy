<?php

namespace App\Filament\Resources\Verifikasis\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VerifikasiInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_laporan')
                    ->numeric(),
                TextEntry::make('id_admin')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('diverifikasi_pada')
                    ->dateTime(),
            ]);
    }
}
