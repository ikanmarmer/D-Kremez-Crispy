<?php

namespace App\Filament\Resources\Penggunas;

use App\Filament\Resources\Penggunas\Pages\CreatePengguna;
use App\Filament\Resources\Penggunas\Pages\EditPengguna;
use App\Filament\Resources\Penggunas\Pages\ListPenggunas;
use App\Filament\Resources\Penggunas\Pages\ViewPengguna;
use App\Filament\Resources\Penggunas\Schemas\PenggunaForm;
use App\Filament\Resources\Penggunas\Schemas\PenggunaInfolist;
use App\Filament\Resources\Penggunas\Tables\PenggunasTable;
use App\Models\Pengguna;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PenggunaResource extends Resource
{
    protected static ?string $model = Pengguna::class;
    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?int $navigationSort = 30;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    public static function form(Schema $schema): Schema
    {
        return PenggunaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PenggunaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PenggunasTable::configure($table);
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
            'index' => ListPenggunas::route('/'),
            'create' => CreatePengguna::route('/create'),
            'view' => ViewPengguna::route('/{record}'),
            'edit' => EditPengguna::route('/{record}/edit'),
        ];
    }
}
