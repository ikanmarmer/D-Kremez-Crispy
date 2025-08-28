<?php

namespace App\Filament\Resources\LaporanPenjualans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LaporanPenjualanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_pengguna')
                    ->numeric(),
                TextEntry::make('tanggal_laporan')
                    ->date(),
                TextEntry::make('total_omzet')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('dikirim_pada')
                    ->dateTime(),
                TextEntry::make('disetujui_pada')
                    ->dateTime(),
                TextEntry::make('dibuat_pada')
                    ->dateTime(),
                TextEntry::make('diperbarui_pada')
                    ->dateTime(),
            ]);
    }
}
