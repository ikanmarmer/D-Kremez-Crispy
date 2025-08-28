<?php

namespace App\Filament\Resources\LaporanPenjualans;

use App\Filament\Resources\LaporanPenjualans\Pages\CreateLaporanPenjualan;
use App\Filament\Resources\LaporanPenjualans\Pages\EditLaporanPenjualan;
use App\Filament\Resources\LaporanPenjualans\Pages\ListLaporanPenjualans;
use App\Filament\Resources\LaporanPenjualans\Pages\ViewLaporanPenjualan;
use App\Filament\Resources\LaporanPenjualans\Schemas\LaporanPenjualanForm;
use App\Filament\Resources\LaporanPenjualans\Schemas\LaporanPenjualanInfolist;
use App\Filament\Resources\LaporanPenjualans\Tables\LaporanPenjualansTable;
use App\Models\LaporanPenjualan;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LaporanPenjualanResource extends Resource
{
    protected static ?string $model = LaporanPenjualan::class;

    protected static ?string $navigationLabel = 'LaporanPenjualan';

    protected static ?int $navigationSort = 20;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    public static function form(Schema $schema): Schema
    {
        return LaporanPenjualanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LaporanPenjualanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LaporanPenjualansTable::configure($table);
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
            'index' => ListLaporanPenjualans::route('/'),
            'create' => CreateLaporanPenjualan::route('/create'),
            'view' => ViewLaporanPenjualan::route('/{record}'),
            'edit' => EditLaporanPenjualan::route('/{record}/edit'),
        ];
    }
}
