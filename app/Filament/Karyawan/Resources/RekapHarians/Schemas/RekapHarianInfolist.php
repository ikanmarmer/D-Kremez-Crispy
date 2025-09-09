<?php

namespace App\Filament\Karyawan\Resources\RekapHarians\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RekapHarianInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_user')
                    ->numeric(),
                TextEntry::make('tanggal')
                    ->date(),
                TextEntry::make('total_produk_terjual')
                    ->numeric(),
                TextEntry::make('omzet_harian')
                    ->numeric(),
            ]);
    }
}
