<?php

namespace App\Filament\Resources\Verifikasis\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VerifikasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_laporan')
                    ->required()
                    ->numeric(),
                TextInput::make('id_admin')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(['disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'])
                    ->required(),
                Textarea::make('alasan')
                    ->default(null)
                    ->columnSpanFull(),
                DateTimePicker::make('diverifikasi_pada')
                    ->required(),
            ]);
    }
}
