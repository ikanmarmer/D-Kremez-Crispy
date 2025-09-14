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
                TextEntry::make('user.name')
                    ->label('nama karyawan')
                    ->numeric(),
                TextEntry::make('tanggal')
                    ->date(),
                TextEntry::make('total_produk_terjual')
                    ->numeric(),
                TextEntry::make('total_omzet')
                    ->numeric(),
                TextEntry::make('produk_terlaris'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
