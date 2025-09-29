<?php

namespace App\Filament\Karyawan\Resources\RekapHarians\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RekapHarianForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->schema([
                Hidden::make('id_users')
                    ->default(fn () => Auth::user()->id),

                TextInput::make('karyawan')
                    ->label('Karyawan')
                    ->default(fn () => Auth::user()?->name)
                    ->disabled()
                    ->dehydrated(false)
                    ->columnSpanFull(),

                DatePicker::make('tanggal')
                    ->required()
                    ->default(fn () => Carbon::now()->toDateString())
                    ->native(false)
                    ->displayFormat('d M Y'),

                TextInput::make('total_omzet')
                    ->label('Total Omzet')
                    ->required()
                    ->numeric()
                    ->minValue(0.0)
                    ->prefix('Rp')
                    ->placeholder('Masukkan omzet harian')
                    ->helperText('Jumlah pemasukan kotor.'),

                TextInput::make('jumlah_pelanggan')
                    ->label('Jumlah Pelanggan')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->placeholder('Contoh: 25')
                    ->helperText('Jumlah pelanggan yang dilayani.'),

                TextInput::make('total_pengeluaran')
                    ->label('Total Pengeluaran')
                    ->required()
                    ->numeric()
                    ->minValue(0.0)
                    ->default(0.0)
                    ->prefix('Rp')
                    ->placeholder('Masukkan pengeluaran harian')
                    ->helperText('Termasuk biaya bahan, operasional, dll.'),

                Textarea::make('catatan')
                    ->label('Catatan Tambahan')
                    ->placeholder('Tuliskan keterangan atau kendala hari ini...')
                    ->columnSpanFull()
                    ->rows(4),
            ]);
    }
}
