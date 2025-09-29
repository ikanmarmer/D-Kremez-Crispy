<?php

namespace App\Filament\Karyawan\Resources\StokMentahs;

use App\Filament\Karyawan\Resources\StokMentahs\Pages\CreateStokMentah;
use App\Filament\Karyawan\Resources\StokMentahs\Pages\EditStokMentah;
use App\Filament\Karyawan\Resources\StokMentahs\Pages\ListStokMentahs;
use App\Filament\Karyawan\Resources\StokMentahs\Pages\ViewStokMentah;
use App\Filament\Karyawan\Resources\StokMentahs\Schemas\StokMentahForm;
use App\Filament\Karyawan\Resources\StokMentahs\Schemas\StokMentahInfolist;
use App\Filament\Karyawan\Resources\StokMentahs\Tables\StokMentahsTable;
use App\Models\StokMentah;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StokMentahResource extends Resource
{
    protected static ?string $model = StokMentah::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'StokMentah';

    public static function form(Schema $schema): Schema
    {
        return StokMentahForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StokMentahInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StokMentahsTable::configure($table);
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
            'index' => ListStokMentahs::route('/'),
            'create' => CreateStokMentah::route('/create'),
            'view' => ViewStokMentah::route('/{record}'),
            'edit' => EditStokMentah::route('/{record}/edit'),
        ];
    }
}
