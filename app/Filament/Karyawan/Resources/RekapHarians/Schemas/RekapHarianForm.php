<?php

namespace App\Filament\Karyawan\Resources\RekapHarians\Schemas;

use Dom\Text;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Facades\Auth;

class RekapHarianForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_users')
                    ->default(fn () => Auth::id()),
                TextInput::make('user.name')
                    ->label('Karyawan')
                    ->default(fn () => Auth::user()->name)
                    ->disabled()
                    ->dehydrated(false),
                DatePicker::make('tanggal')
                    ->required(),
                TextInput::make('total_produk_terjual')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_omzet')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('produk_terlaris'),
                Textarea::make('catatan')
                    ->columnSpanFull(),
            ]);
    }
}
