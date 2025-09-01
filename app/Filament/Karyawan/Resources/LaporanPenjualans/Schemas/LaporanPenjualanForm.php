<?php

namespace App\Filament\Karyawan\Resources\LaporanPenjualans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LaporanPenjualanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_pengguna')
                    ->required()
                    ->numeric(),
                DatePicker::make('tanggal_laporan')
                    ->required(),
                TextInput::make('total_omzet')
                    ->required()
                    ->numeric(),
                Textarea::make('catatan')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
            'draf' => 'Draf',
            'dikirim' => 'Dikirim',
            'ditandai' => 'Ditandai',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
        ])
                    ->default('draf')
                    ->required(),
                DateTimePicker::make('dikirim_pada')
                    ->required(),
                DateTimePicker::make('disetujui_pada'),
                DateTimePicker::make('dibuat_pada')
                    ->required(),
                DateTimePicker::make('diperbarui_pada')
                    ->required(),
            ]);
    }
}
