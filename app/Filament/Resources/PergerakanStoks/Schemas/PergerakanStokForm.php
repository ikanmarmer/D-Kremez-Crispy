<?php

namespace App\Filament\Resources\PergerakanStoks\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PergerakanStokForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_produk')
                    ->required()
                    ->numeric(),
                Select::make('jenis_referensi')
                    ->options(['laporan' => 'Laporan', 'manual' => 'Manual', 'retur' => 'Retur', 'rusak' => 'Rusak'])
                    ->required(),
                TextInput::make('id_referensi')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('tanggal_pergerakan')
                    ->required(),
                TextInput::make('jumlah_perubahan')
                    ->required()
                    ->numeric(),
                Textarea::make('catatan')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
