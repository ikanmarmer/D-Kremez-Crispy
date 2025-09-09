<?php

namespace App\Filament\Karyawan\Resources\LaporanPenjualans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class LaporanPenjualanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Hidden::make('id_users')
                ->default(fn () => Auth::id()),


            TextInput::make('user_name')
                ->default(fn () => Auth::user()->name)
                ->label('Nama Karyawan')
                ->disabled()
                ->dehydrated(false),

            DatePicker::make('tanggal_laporan')
                ->required(),

            TextInput::make('total_produk_terjual')
                ->required(),

            TextInput::make('omzet')
                ->required()
                ->numeric(),

            Select::make('status')
                ->options([
                    'draf' => 'Draf',
                    'dikirim' => 'Dikirim',
                    'ditandai' => 'Ditandai',
                    'disetujui' => 'Disetujui',
                    'ditolak' => 'Ditolak',
                ])
                ->required(),

            DateTimePicker::make('dikirim_pada')
                ->default(now())
                ->required(),

            DateTimePicker::make('disetujui_pada'),

            Textarea::make('catatan')
                ->columnSpanFull(),
        ]);
    }
}
