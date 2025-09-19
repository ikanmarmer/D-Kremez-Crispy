<?php

namespace App\Filament\Resources\Testimonis;

use App\Filament\Resources\Testimonis\Pages\ListTestimonis;
use App\Filament\Resources\Testimonis\Pages\ViewTestimoni;
use App\Filament\Resources\Testimonis\Schemas\TestimoniForm;
use App\Filament\Resources\Testimonis\Schemas\TestimoniInfolist;
use App\Filament\Resources\Testimonis\Tables\TestimonisTable;
use App\Models\Testimoni;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class TestimoniResource extends Resource
{
    protected static ?string $model = Testimoni::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static string|UnitEnum|null $navigationGroup = 'Kelola';

    protected static ?string $modelLabel = 'Testimoni';

    protected static ?string $pluralModelLabel = 'Testimoni';


    public static function form(Schema $schema): Schema
    {
        return TestimoniForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TestimoniInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TestimonisTable::configure($table);
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
            'index' => ListTestimonis::route('/'),

            'view' => ViewTestimoni::route('/{record}'),
        ];
    }
}
