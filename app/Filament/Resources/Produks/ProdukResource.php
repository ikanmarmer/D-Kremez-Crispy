<?php

namespace App\Filament\Resources\Produks;

use App\Filament\Resources\Produks\Pages\CreateProduk;
use App\Filament\Resources\Produks\Pages\EditProduk;
use App\Filament\Resources\Produks\Pages\ListProduks;
use App\Filament\Resources\Produks\Pages\ViewProduk;
use App\Filament\Resources\Produks\Schemas\ProdukForm;
use App\Filament\Resources\Produks\Schemas\ProdukInfolist;
use App\Filament\Resources\Produks\Tables\ProduksTable;
use App\Models\Produk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationLabel = 'Produk';
    protected static ?int $navigationSort = 60;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

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
