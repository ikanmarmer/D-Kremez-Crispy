<?php

namespace App\Filament\Resources\DetailLaporanPenjualans;

use App\Filament\Resources\DetailLaporanPenjualans\Pages\CreateDetailLaporanPenjualan;
use App\Filament\Resources\DetailLaporanPenjualans\Pages\EditDetailLaporanPenjualan;
use App\Filament\Resources\DetailLaporanPenjualans\Pages\ListDetailLaporanPenjualans;
use App\Filament\Resources\DetailLaporanPenjualans\Pages\ViewDetailLaporanPenjualan;
use App\Filament\Resources\DetailLaporanPenjualans\Schemas\DetailLaporanPenjualanForm;
use App\Filament\Resources\DetailLaporanPenjualans\Schemas\DetailLaporanPenjualanInfolist;
use App\Filament\Resources\DetailLaporanPenjualans\Tables\DetailLaporanPenjualansTable;
use App\Models\DetailLaporanPenjualan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DetailLaporanPenjualanResource extends Resource
{
    protected static ?string $model = DetailLaporanPenjualan::class;

    protected static ?string $navigationLabel = 'Detail Laporan Penjualan';

    protected static ?int $navigationSort = 30;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    public static function form(Schema $schema): Schema
    {
        return DetailLaporanPenjualanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DetailLaporanPenjualanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DetailLaporanPenjualansTable::configure($table);
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
            'index' => ListDetailLaporanPenjualans::route('/'),
            'create' => CreateDetailLaporanPenjualan::route('/create'),
            'view' => ViewDetailLaporanPenjualan::route('/{record}'),
            'edit' => EditDetailLaporanPenjualan::route('/{record}/edit'),
        ];
    }
}
