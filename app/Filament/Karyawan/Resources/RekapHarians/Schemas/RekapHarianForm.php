<?php

namespace App\Filament\Karyawan\Resources\RekapHarians\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class RekapHarianForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('id_users')
                    ->default(fn () => Auth::id()),
                TextInput::make('user.name')
                    ->label('Karyawan')
                    ->default(fn () => Auth::user()->name)
                    ->disabled()
                    ->dehydrated(false),
                DatePicker::make('tanggal')
                    ->required()
                    ->default(fn () => now()->format('Y-m-d')),
                TextInput::make('total_omzet')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                TextInput::make('jumlah_pelanggan')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_pengeluaran')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('Rp'),
                Textarea::make('catatan')
                    ->columnSpanFull(),
            ]);
    }
}
