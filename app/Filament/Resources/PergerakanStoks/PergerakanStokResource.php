<?php

namespace App\Filament\Resources\PergerakanStoks;

use App\Filament\Resources\PergerakanStoks\Pages\CreatePergerakanStok;
use App\Filament\Resources\PergerakanStoks\Pages\EditPergerakanStok;
use App\Filament\Resources\PergerakanStoks\Pages\ListPergerakanStoks;
use App\Filament\Resources\PergerakanStoks\Pages\ViewPergerakanStok;
use App\Filament\Resources\PergerakanStoks\Schemas\PergerakanStokForm;
use App\Filament\Resources\PergerakanStoks\Schemas\PergerakanStokInfolist;
use App\Filament\Resources\PergerakanStoks\Tables\PergerakanStoksTable;
use App\Models\PergerakanStok;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PergerakanStokResource extends Resource
{
    protected static ?string $model = PergerakanStok::class;

    protected static ?string $navigationLabel = 'Pergerakan Stok';

    protected static ?int $navigationSort = 70;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    public static function form(Schema $schema): Schema
    {
        return PergerakanStokForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PergerakanStokInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PergerakanStoksTable::configure($table);
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
            'index' => ListPergerakanStoks::route('/'),
            'create' => CreatePergerakanStok::route('/create'),
            'view' => ViewPergerakanStok::route('/{record}'),
            'edit' => EditPergerakanStok::route('/{record}/edit'),
        ];
    }
}
