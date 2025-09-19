<?php

namespace App\Filament\Karyawan\Resources\LaporanPenjualans;

use App\Filament\Karyawan\Resources\LaporanPenjualans\Pages\CreateLaporanPenjualan;
use App\Filament\Karyawan\Resources\LaporanPenjualans\Pages\EditLaporanPenjualan;
use App\Filament\Karyawan\Resources\LaporanPenjualans\Pages\ListLaporanPenjualans;
use App\Filament\Karyawan\Resources\LaporanPenjualans\Pages\ViewLaporanPenjualan;
use App\Filament\Karyawan\Resources\LaporanPenjualans\Schemas\LaporanPenjualanForm;
use App\Filament\Karyawan\Resources\LaporanPenjualans\Schemas\LaporanPenjualanInfolist;
use App\Filament\Karyawan\Resources\LaporanPenjualans\Tables\LaporanPenjualansTable;
use App\Models\LaporanPenjualan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LaporanPenjualanResource extends Resource
{
    protected static ?string $model = LaporanPenjualan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'LaporanPenjualan';

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
    public static function mutateFormDataBeforeCreate(array $data): array
    {
        Log::info('mutateFormDataBeforeCreate called', ['auth_id' => Auth::id(), 'data_before' => $data]);

        $data['id_users'] = Auth::id();
        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        $data['id_users'] = Auth::id();
        return $data;
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }
}
