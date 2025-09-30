<?php

namespace App\Filament\Karyawan\Resources\StokMentahs\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;

class StokMentahInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Stok Mentah')
                    ->description('Detail informasi stok bahan mentah')
                    ->icon('heroicon-o-cube')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Group::make([
                                    TextEntry::make('nama')
                                        ->label('Nama Bahan')
                                        ->size(TextSize::Large)
                                        ->weight('font-semibold')
                                        ->color('primary'),

                                    TextEntry::make('tanggal')
                                        ->label('Tanggal Stok')
                                        ->date('d F Y')
                                        ->icon('heroicon-o-calendar')
                                        ->color('gray'),
                                ])->columnSpan(2),

                                TextEntry::make('jumlah')
                                    ->label('Jumlah Stok')
                                    ->numeric()
                                    ->size(TextSize::Large)
                                    ->weight('font-bold')
                                    ->color('success')
                                    ->alignCenter(),
                            ]),
                    ]),

                Section::make('Informasi Harga')
                    ->description('Rincian harga dan nilai stok')
                    ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('harga')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->money('IDR')
                                    ->icon('heroicon-o-banknotes')
                                    ->color('success'),

                                TextEntry::make('jumlah')
                                    ->label('Jumlah Stok')
                                    ->numeric()
                                    ->icon('heroicon-o-cube'),

                                TextEntry::make('harga_total_stok')
                                    ->label('Total Harga')
                                    ->numeric()
                                    ->money('IDR')
                                    ->weight('font-bold')
                                    ->color('primary')
                                    ->icon('heroicon-o-calculator'),
                            ]),
                    ]),


            ]);
    }
}
