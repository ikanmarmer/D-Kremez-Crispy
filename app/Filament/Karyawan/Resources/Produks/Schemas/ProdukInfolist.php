<?php

namespace App\Filament\Karyawan\Resources\Produks\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;

class ProdukInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('🛍️ Informasi Produk')
                    ->description('Detail lengkap produk')
                    ->columns(3)
                    ->schema([
                        ImageEntry::make('image')
                            ->label('')
                            ->placeholder('Tidak ada gambar')
                            ->height(150)
                            ->width(150)
                            ->extraAttributes([
                                'class' => 'rounded-xl shadow-xl border-4 border-white dark:border-gray-700'
                            ])
                            ->columnSpan(1),

                        TextEntry::make('nama')
                            ->label('Nama Produk')
                            ->placeholder('Nama tidak tersedia')
                            ->icon('heroicon-o-cube')
                            ->color('primary')
                            ->weight('bold')
                            ->size('xl')
                            ->extraAttributes(['class' => 'text-2xl font-bold mb-2'])
                            ->columnSpan(2),

                        IconEntry::make('aktif')
                            ->label('Status')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-badge')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger')
                            ->size(IconSize::Large)
                            ->columnSpan(1),

                        TextEntry::make('kategori')
                            ->label('Kategori')
                            ->placeholder('-')
                            ->icon('heroicon-o-tag')
                            ->color('info')
                            ->badge()
                            ->size('lg')
                            ->columnSpan(2),
                    ]),

                Section::make('💰 Harga')
                    ->description('Informasi harga')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('harga')
                            ->label('Harga Jual')
                            ->placeholder('Belum ditetapkan')
                            ->money('IDR', locale: 'id')
                            ->icon('heroicon-o-banknotes')
                            ->color('success')
                            ->weight('bold')
                            ->size('xl')
                            ->copyable()
                            ->copyMessage('Harga disalin!')
                            ->extraAttributes([
                                'class' => 'text-3xl font-black text-emerald-600 dark:text-emerald-400'
                            ]),

                        TextEntry::make('harga_terbilang')
                            ->label('Terbilang')
                            ->state(function ($record) {
                                if ($record && $record->harga) {
                                    return self::numberToWords($record->harga) . ' rupiah';
                                }
                                return '-';
                            })
                            ->placeholder('-')
                            ->icon('heroicon-o-document-text')
                            ->color('slate')
                            ->size('sm')
                            ->columnSpan(1),
                    ]),

                Section::make('📄 Deskripsi')
                    ->description('Detail dan spesifikasi produk')
                    ->schema([
                        TextEntry::make('deskripsi')
                            ->label('')
                            ->placeholder('Tidak ada deskripsi tersedia')
                            ->html()
                            ->prose()
                            ->extraAttributes([
                                'class' => 'text-justify leading-relaxed p-4 bg-gray-50 dark:bg-gray-800 rounded-lg'
                            ])
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->persistCollapsed(),

                Section::make('⚙️ Info Sistem')
                    ->description('Data teknis dan riwayat')
                    ->columns(2)
                    ->collapsed()
                    ->persistCollapsed()
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime('d M Y, H:i')
                            ->placeholder('-')
                            ->icon('heroicon-o-calendar-days')
                            ->color('success'),

                        TextEntry::make('updated_at')
                            ->label('Diperbarui')
                            ->dateTime('d M Y, H:i')
                            ->placeholder('Belum pernah')
                            ->icon('heroicon-o-pencil-square')
                            ->color('warning'),
                    ]),
            ]);
    }

    /**
     * Convert number to Indonesian words (optimized)
     */
    private static function numberToWords(int $number): string
    {
        if ($number == 0) return 'nol';

        $ones = [
            '', 'satu', 'dua', 'tiga', 'empat', 'lima',
            'enam', 'tujuh', 'delapan', 'sembilan'
        ];

        $result = '';

        // Billions
        if ($number >= 1000000000) {
            $billions = intval($number / 1000000000);
            $result .= self::convertHundreds($billions, $ones) . ' miliar';
            $number %= 1000000000;
            if ($number > 0) $result .= ' ';
        }

        // Millions
        if ($number >= 1000000) {
            $millions = intval($number / 1000000);
            $result .= self::convertHundreds($millions, $ones) . ' juta';
            $number %= 1000000;
            if ($number > 0) $result .= ' ';
        }

        // Thousands
        if ($number >= 1000) {
            $thousands = intval($number / 1000);
            if ($thousands == 1) {
                $result .= 'seribu';
            } else {
                $result .= self::convertHundreds($thousands, $ones) . ' ribu';
            }
            $number %= 1000;
            if ($number > 0) $result .= ' ';
        }

        // Hundreds, tens, and ones
        if ($number > 0) {
            $result .= self::convertHundreds($number, $ones);
        }

        return trim($result);
    }

    /**
     * Helper function to convert hundreds
     */
    private static function convertHundreds(int $number, array $ones): string
    {
        $result = '';

        // Hundreds
        if ($number >= 100) {
            $hundreds = intval($number / 100);
            if ($hundreds == 1) {
                $result .= 'seratus';
            } else {
                $result .= $ones[$hundreds] . ' ratus';
            }
            $number %= 100;
            if ($number > 0) $result .= ' ';
        }

        // Tens and ones
        if ($number >= 20) {
            $tens = intval($number / 10);
            $result .= $ones[$tens] . ' puluh';
            $number %= 10;
            if ($number > 0) $result .= ' ' . $ones[$number];
        } elseif ($number >= 10) {
            $teens = [
                'sepuluh', 'sebelas', 'dua belas', 'tiga belas', 'empat belas',
                'lima belas', 'enam belas', 'tujuh belas', 'delapan belas', 'sembilan belas'
            ];
            $result .= $teens[$number - 10];
        } elseif ($number > 0) {
            $result .= $ones[$number];
        }

        return trim($result);
    }
}
