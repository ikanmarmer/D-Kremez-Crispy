<?php

namespace App\Filament\Resources\DetailLaporanPenjualans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DetailLaporanPenjualanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_laporan')
                    ->numeric(),
                TextEntry::make('id_produk')
                    ->numeric(),
                TextEntry::make('jumlah_terjual')
                    ->numeric(),
                TextEntry::make('harga_satuan')
                    ->numeric(),
                TextEntry::make('total_harga')
                    ->numeric(),
            ]);
    }
}
