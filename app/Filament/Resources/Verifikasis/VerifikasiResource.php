<?php

namespace App\Filament\Resources\Verifikasis;

use App\Filament\Resources\Verifikasis\Pages\CreateVerifikasi;
use App\Filament\Resources\Verifikasis\Pages\EditVerifikasi;
use App\Filament\Resources\Verifikasis\Pages\ListVerifikasis;
use App\Filament\Resources\Verifikasis\Pages\ViewVerifikasi;
use App\Filament\Resources\Verifikasis\Schemas\VerifikasiForm;
use App\Filament\Resources\Verifikasis\Schemas\VerifikasiInfolist;
use App\Filament\Resources\Verifikasis\Tables\VerifikasisTable;
use App\Models\Verifikasi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VerifikasiResource extends Resource
{
    protected static ?string $model = Verifikasi::class;

    protected static ?string $navigationLabel = 'Verifikasi';

    protected static ?int $navigationSort = 90;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck ;

    public static function form(Schema $schema): Schema
    {
        return VerifikasiForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VerifikasiInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VerifikasisTable::configure($table);
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
            'index' => ListVerifikasis::route('/'),
            'create' => CreateVerifikasi::route('/create'),
            'view' => ViewVerifikasi::route('/{record}'),
            'edit' => EditVerifikasi::route('/{record}/edit'),
        ];
    }
}
