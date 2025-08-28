<?php

namespace App\Filament\Resources\PengaturanTampilans;

use App\Filament\Resources\PengaturanTampilans\Pages\CreatePengaturanTampilan;
use App\Filament\Resources\PengaturanTampilans\Pages\EditPengaturanTampilan;
use App\Filament\Resources\PengaturanTampilans\Pages\ListPengaturanTampilans;
use App\Filament\Resources\PengaturanTampilans\Pages\ViewPengaturanTampilan;
use App\Filament\Resources\PengaturanTampilans\Schemas\PengaturanTampilanForm;
use App\Filament\Resources\PengaturanTampilans\Schemas\PengaturanTampilanInfolist;
use App\Filament\Resources\PengaturanTampilans\Tables\PengaturanTampilansTable;
use App\Models\PengaturanTampilan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PengaturanTampilanResource extends Resource
{
    protected static ?string $model = PengaturanTampilan::class;

    protected static ?string $navigationLabel = 'Pengaturan Tampilan';

    protected static ?int $navigationSort = 40;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    public static function form(Schema $schema): Schema
    {
        return PengaturanTampilanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PengaturanTampilanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengaturanTampilansTable::configure($table);
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
            'index' => ListPengaturanTampilans::route('/'),
            'create' => CreatePengaturanTampilan::route('/create'),
            'view' => ViewPengaturanTampilan::route('/{record}'),
            'edit' => EditPengaturanTampilan::route('/{record}/edit'),
        ];
    }
}
