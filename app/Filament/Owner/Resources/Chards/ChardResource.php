<?php

namespace App\Filament\Owner\Resources\Chards;

use App\Filament\Owner\Resources\Chards\Pages\CreateChard;
use App\Filament\Owner\Resources\Chards\Pages\EditChard;
use App\Filament\Owner\Resources\Chards\Pages\ListChards;
use App\Filament\Owner\Resources\Chards\Schemas\ChardForm;
use App\Filament\Owner\Resources\Chards\Tables\ChardsTable;
use App\Models\Chard;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ChardResource extends Resource
{
    protected static ?string $model = Chard::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ChardForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChardsTable::configure($table);
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
            'index' => ListChards::route('/'),
            'create' => CreateChard::route('/create'),
            'edit' => EditChard::route('/{record}/edit'),
        ];
    }
}
