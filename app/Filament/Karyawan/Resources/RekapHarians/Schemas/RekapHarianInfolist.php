<?php

namespace App\Filament\Karyawan\Resources\RekapHarians\Schemas;


use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Facades\Auth;

class RekapHarianInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('id_users')
                    ->default(fn () => Auth::id()),
                TextEntry::make('user.name')
                    ->label('Karyawan')
                    ->default(fn () => Auth::user()->name)
                    ->disabled()
                    ->dehydrated(false),
                TextEntry::make('tanggal')
                    ->date(),
                TextEntry::make('total_omzet')
                    ->numeric(),
                TextEntry::make('jumlah_pelanggan')
                    ->numeric(),
                TextEntry::make('total_pengeluaran')
                    ->numeric(),
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
