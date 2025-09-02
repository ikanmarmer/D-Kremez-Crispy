<?php

namespace App\Filament\Owner\Resources\RekapHarians;

use App\Filament\Owner\Resources\RekapHarians\Pages\CreateRekapHarian;
use App\Filament\Owner\Resources\RekapHarians\Pages\EditRekapHarian;
use App\Filament\Owner\Resources\RekapHarians\Pages\ListRekapHarians;
use App\Filament\Owner\Resources\RekapHarians\Pages\ViewRekapHarian;
use App\Filament\Owner\Resources\RekapHarians\Schemas\RekapHarianForm;
use App\Filament\Owner\Resources\RekapHarians\Schemas\RekapHarianInfolist;
use App\Filament\Owner\Resources\RekapHarians\Tables\RekapHariansTable;
use App\Models\RekapHarian;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RekapHarianResource extends Resource
{
    protected static ?string $model = RekapHarian::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Rekap Harian';

    public static function form(Schema $schema): Schema
    {
        return RekapHarianForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RekapHarianInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RekapHariansTable::configure($table);
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
            'index' => ListRekapHarians::route('/'),
            'create' => CreateRekapHarian::route('/create'),
            'view' => ViewRekapHarian::route('/{record}'),
            'edit' => EditRekapHarian::route('/{record}/edit'),
        ];
    }
}
