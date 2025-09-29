<?php

namespace App\Filament\Karyawan\Resources\Produks\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProdukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama Produk')
                    ->required()
                    ->maxLength(50),
                TextInput::make('harga')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0)
                    ->default(0),
                FileUpload::make('image')
                    ->image(),
                Toggle::make('aktif')
                    ->required(),
                Textarea::make('deskripsi')
                    ->required()
                    ->label('Deskripsi Produk')
                    ->rows(3)
                    ->placeholder('Masukkan deskripsi produk')
                    ->maxLength(50)
                    ->columnSpanFull(),
                TextInput::make('kategori'),
            ]);
    }
}
