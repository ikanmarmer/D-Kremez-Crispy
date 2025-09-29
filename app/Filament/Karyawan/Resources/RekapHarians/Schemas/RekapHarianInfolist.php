<?php

namespace App\Filament\Karyawan\Resources\RekapHarians\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Facades\Auth;

class RekapHarianInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                // Hidden field untuk tracking user
                Hidden::make('id_users')
                    ->default(fn () => Auth::user()->id),

                // Section: Informasi Karyawan
                Section::make('📋 Informasi Karyawan')
                    ->description('Data karyawan yang membuat rekap')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Nama Karyawan')
                            ->default(fn () => Auth::user()->name)
                            ->placeholder('Tidak ada data')
                            ->icon('heroicon-o-user')
                            ->color('primary')
                            ->weight('bold')
                            ->columnSpanFull(),

                        TextEntry::make('tanggal')
                            ->label('Tanggal Rekap')
                            ->date('d F Y')
                            ->placeholder('Tanggal tidak tersedia')
                            ->icon('heroicon-o-calendar-days')
                            ->color('gray'),
                    ]),

                // Section: Data Rekap Harian
                Section::make('💰 Rekap Penjualan Harian')
                    ->description('Ringkasan omzet dan data penjualan hari ini')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('total_omzet')
                            ->label('Total Omzet')
                            ->money('IDR', locale: 'id')
                            ->placeholder('Rp 0')
                            ->icon('heroicon-o-banknotes')
                            ->color('success')
                            ->weight('bold')
                            ->copyable()
                            ->copyMessage('Omzet berhasil disalin!'),

                        TextEntry::make('jumlah_pelanggan')
                            ->label('Jumlah Pelanggan')
                            ->numeric()
                            ->placeholder('0 pelanggan')
                            ->icon('heroicon-o-user-group')
                            ->color('info')
                            ->suffix(' orang'),

                        TextEntry::make('total_pengeluaran')
                            ->label('Total Pengeluaran')
                            ->money('IDR', locale: 'id')
                            ->placeholder('Rp 0')
                            ->icon('heroicon-o-arrow-trending-down')
                            ->color('warning')
                            ->copyable()
                            ->copyMessage('Pengeluaran berhasil disalin!'),

                        TextEntry::make('laba_bersih')
                            ->label('Laba Bersih')
                            ->state(function ($record) {
                                if ($record && $record->total_omzet && $record->total_pengeluaran) {
                                    return $record->total_omzet - $record->total_pengeluaran;
                                }
                                return 0;
                            })
                            ->money('IDR', locale: 'id')
                            ->icon('heroicon-o-chart-bar')
                            ->color(function ($state) {
                                return $state > 0 ? 'success' : ($state < 0 ? 'danger' : 'gray');
                            })
                            ->weight('bold'),
                    ]),

                // Section: Catatan
                Section::make('📝 Catatan & Keterangan')
                    ->description('Informasi tambahan dan catatan khusus')
                    ->schema([
                        TextEntry::make('catatan')
                            ->label('Catatan')
                            ->placeholder('Tidak ada catatan')
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->persistCollapsed(),

                // Section: Informasi Sistem
                Section::make('⚙️ Informasi Sistem')
                    ->description('Data teknis dan riwayat perubahan')
                    ->columns(2)
                    ->collapsed()
                    ->persistCollapsed()
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime('d F Y, H:i:s')
                            ->placeholder('Data tidak tersedia')
                            ->icon('heroicon-o-plus-circle')
                            ->color('success'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diperbarui')
                            ->dateTime('d F Y, H:i:s')
                            ->placeholder('Belum pernah diperbarui')
                            ->icon('heroicon-o-pencil-square')
                            ->color('warning'),

                        TextEntry::make('created_by')
                            ->label('Dibuat Oleh')
                            ->default(fn () => Auth::user()->name)
                            ->icon('heroicon-o-user')
                            ->color('gray'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->default('Aktif')
                            ->color('success'),
                    ]),
            ]);
    }
}
