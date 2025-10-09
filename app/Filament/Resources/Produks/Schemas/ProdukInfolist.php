<?php

namespace App\Filament\Resources\Produks\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Flex;
use Filament\Support\Enums\TextSize;
use Filament\Schemas\Schema;

class ProdukInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                // Section Informasi Produk
                Section::make('Informasi Produk')
                    ->schema([
                        Flex::make([
                            ImageEntry::make('image')
                                ->label('Gambar Produk')
                                ->disk('public')
                                ->extraImgAttributes([
                                    'class' => 'mr-4 ring-4 ring-white dark:ring-gray-800 shadow-xl cursor-pointer rounded-lg border-gray-200 dark:border-gray-600 object-cover',
                                ])
                                ->grow(false),

                            Flex::make([
                                // Nama Produk
                                TextEntry::make('nama')
                                    ->label('Nama Produk')
                                    ->icon('heroicon-o-cube')
                                    ->extraAttributes([
                                        'class' => 'text-lg break-words',
                                    ])
                                    ->columnOrder([
                                        'default' => 1,
                                        'md' => 1,
                                    ]),

                                // Kategori (letakkan sebelum status desktop)
                                TextEntry::make('kategori')
                                    ->label('Kategori')
                                    ->icon('heroicon-o-tag')
                                    ->badge()
                                    ->extraAttributes([
                                        'class' => 'px-2 py-1 text-lg',
                                    ])
                                    ->columnOrder([
                                        'default' => 2,  // mobile setelah nama
                                        'md' => 3,      // di desktop posisinya setelah status
                                    ]),

                                // Status
                                IconEntry::make('aktif')
                                    ->boolean()
                                    ->trueIcon('heroicon-o-check-circle')
                                    ->falseIcon('heroicon-o-x-circle')
                                    ->extraAttributes([
                                        'class' => 'flex items-start text-lg px-2 py-1 rounded-lg bg-gray-50 dark:bg-gray-900',
                                    ])
                                    ->columnOrder([
                                        'default' => 3,  // mobile setelah kategori
                                        'md' => 2,       // desktop status tampil sebelum kategori
                                    ]),
                            ])
                                ->grow(true)
                                ->extraAttributes([
                                    'class' => 'gap-4 items-start',
                                ]),
                        ])
                            ->extraAttributes([
                                'class' => 'items-start gap-6',
                            ]),
                    ])
                    ->extraAttributes([
                        'class' => 'bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4',
                    ]),

                // Section Harga
                Section::make('Informasi Harga')
                    ->description('Detail harga produk')
                    ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('harga')
                                    ->label('Harga Jual')
                                    ->placeholder('Belum ditetapkan')
                                    ->money('IDR', locale: 'id')
                                    ->icon('heroicon-o-banknotes')
                                    ->color('success')
                                    ->weight('font-bold')
                                    ->size(TextSize::Large)
                                    ->copyable()
                                    ->copyMessage('Harga disalin!')
                                    ->extraAttributes([
                                        'class' => 'text-2xl font-bold text-green-600 dark:text-green-400',
                                    ]),

                                TextEntry::make('harga_terbilang')
                                    ->label('Terbilang')
                                    ->state(function ($record) {
                                        if ($record && $record->harga) {
                                            return self::numberToWords($record->harga) . ' rupiah';
                                        }

                                        return 'Tidak ada harga';
                                    })
                                    ->placeholder('Tidak ada harga')
                                    ->icon('heroicon-o-document-text')
                                    ->color('gray')
                                    ->size(TextSize::Small)
                                    ->extraAttributes([
                                        'class' => 'italic text-gray-600 dark:text-gray-400',
                                    ]),
                            ]),
                    ]),

                // Section Deskripsi
                Section::make('Deskripsi Produk')
                    ->description('Penjelasan detail tentang produk')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        TextEntry::make('deskripsi')
                            ->label('')
                            ->placeholder('Tidak ada deskripsi yang tersedia untuk produk ini.')
                            ->prose()
                            ->markdown()
                            ->extraAttributes([
                                'class' => 'p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm min-h-[120px] text-gray-700 dark:text-gray-300 leading-relaxed text-justify',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                // Section Informasi Sistem
                Section::make('Informasi Sistem')
                    ->description('Data teknis dan riwayat')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->dateTime('d F Y, H:i')
                                    ->placeholder('-')
                                    ->icon('heroicon-o-calendar')
                                    ->color('gray')
                                    ->size(TextSize::Small),

                                TextEntry::make('updated_at')
                                    ->label('Diperbarui Pada')
                                    ->dateTime('d F Y, H:i')
                                    ->placeholder('Belum pernah diperbarui')
                                    ->icon('heroicon-o-arrow-path')
                                    ->color('gray')
                                    ->size(TextSize::Small),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(true),
            ]);
    }

    /**
     * Convert number to Indonesian words
     */
    private static function numberToWords(int $number): string
    {
        if ($number == 0) {
            return 'nol';
        }

        $ones = [
            '',
            'satu',
            'dua',
            'tiga',
            'empat',
            'lima',
            'enam',
            'tujuh',
            'delapan',
            'sembilan',
        ];

        $result = '';

        // Billions
        if ($number >= 1000000000) {
            $billions = intval($number / 1000000000);
            $result .= self::convertHundreds($billions, $ones) . ' miliar';
            $number %= 1000000000;
            if ($number > 0) {
                $result .= ' ';
            }
        }

        // Millions
        if ($number >= 1000000) {
            $millions = intval($number / 1000000);
            $result .= self::convertHundreds($millions, $ones) . ' juta';
            $number %= 1000000;
            if ($number > 0) {
                $result .= ' ';
            }
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
            if ($number > 0) {
                $result .= ' ';
            }
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
            if ($number > 0) {
                $result .= ' ';
            }
        }

        // Tens and ones
        if ($number >= 20) {
            $tens = intval($number / 10);
            $result .= $ones[$tens] . ' puluh';
            $number %= 10;
            if ($number > 0) {
                $result .= ' ' . $ones[$number];
            }
        } elseif ($number >= 10) {
            $teens = [
                'sepuluh',
                'sebelas',
                'dua belas',
                'tiga belas',
                'empat belas',
                'lima belas',
                'enam belas',
                'tujuh belas',
                'delapan belas',
                'sembilan belas',
            ];
            $result .= $teens[$number - 10];
        } elseif ($number > 0) {
            $result .= $ones[$number];
        }

        return trim($result);
    }
}
