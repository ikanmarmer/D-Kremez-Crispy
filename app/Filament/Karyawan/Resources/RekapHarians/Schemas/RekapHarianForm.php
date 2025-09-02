<?php

namespace App\Filament\Karyawan\Resources\RekapHarians\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RekapHarianForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_user')
                    ->required()
                    ->numeric(),
                DatePicker::make('tanggal')
                    ->required(),
                TextInput::make('total_produk_terjual')
                    ->required()
                    ->numeric(),
                TextInput::make('omzet_harian')
                    ->required()
                    ->numeric(),
                Textarea::make('sisa_stok')
                    ->columnSpanFull(),
            ]);
    }
}
