<?php

namespace App\Filament\Resources\Testimonis\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TestimoniForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_pelanggan')
                    ->required()
                    ->numeric(),
                Textarea::make('konten')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('penilaian')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'])
                    ->default('menunggu')
                    ->required(),
                TextInput::make('dimoderasi_oleh')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('dimoderasi_pada'),
                DateTimePicker::make('dibuat_pada')
                    ->required(),
                DateTimePicker::make('diperbarui_pada')
                    ->required(),
            ]);
    }
}
