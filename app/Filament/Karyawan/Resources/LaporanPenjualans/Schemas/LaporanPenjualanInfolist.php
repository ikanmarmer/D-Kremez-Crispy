<?php

namespace App\Filament\Karyawan\Resources\LaporanPenjualans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LaporanPenjualanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('users.name')
                    ->numeric(),
                TextEntry::make('tanggal_laporan')
                    ->date(),
                TextEntry::make('total_produk_terjual'),
                TextEntry::make('omzet')
                    ->numeric(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('dikirim_pada')
                    ->dateTime(),
                TextEntry::make('disetujui_pada')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('catatan')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
