<?php

namespace App\Filament\Resources\PergerakanStoks\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PergerakanStokInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_produk')
                    ->numeric(),
                TextEntry::make('jenis_referensi'),
                TextEntry::make('id_referensi')
                    ->numeric(),
                TextEntry::make('tanggal_pergerakan')
                    ->dateTime(),
                TextEntry::make('jumlah_perubahan')
                    ->numeric(),
            ]);
    }
}
