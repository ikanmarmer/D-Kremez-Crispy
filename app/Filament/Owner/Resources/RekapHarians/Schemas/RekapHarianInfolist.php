<?php

namespace App\Filament\Owner\Resources\RekapHarians\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RekapHarianInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_users')
                    ->numeric(),
                TextEntry::make('id_produk')
                    ->numeric(),
                TextEntry::make('tanggal_rekap')
                    ->date(),
                TextEntry::make('jumlah_produk_terjual')
                    ->numeric(),
                TextEntry::make('total_omzet')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
