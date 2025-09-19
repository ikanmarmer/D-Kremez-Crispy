<?php

namespace App\Filament\Karyawan\Resources\Produks;

use App\Filament\Karyawan\Resources\Produks\Pages\CreateProduk;
use App\Filament\Karyawan\Resources\Produks\Pages\EditProduk;
use App\Filament\Karyawan\Resources\Produks\Pages\ListProduks;
use App\Filament\Karyawan\Resources\Produks\Pages\ViewProduk;
use App\Filament\Karyawan\Resources\Produks\Schemas\ProdukForm;
use App\Filament\Karyawan\Resources\Produks\Schemas\ProdukInfolist;
use App\Filament\Karyawan\Resources\Produks\Tables\ProduksTable;
use App\Models\Produk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Produk';

    public static function form(Schema $schema): Schema
    {
        return ProdukForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProdukInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProduksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProduks::route('/'),
            'create' => CreateProduk::route('/create'),
            'view' => ViewProduk::route('/{record}'),
            'edit' => EditProduk::route('/{record}/edit'),
        ];
    }
}
