<?php

namespace App\Filament\Karyawan\Resources\LaporanPenjualans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;

class LaporanPenjualanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('nama karyawan')
                    ->numeric(),
                TextEntry::make('tanggal_laporan')
                    ->date(),
                TextEntry::make('total_produk_terjual'),
                TextEntry::make('omzet')
                    ->numeric(),
                TextEntry::make('status')
                    ->numeric()
                    ->label('status laporan'),
                TextEntry::make('dikirim_pada')
                    ->dateTime(),
                TextEntry::make('disetujui_pada')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
