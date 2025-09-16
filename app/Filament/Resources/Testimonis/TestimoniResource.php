<?php

namespace App\Filament\Resources\Testimonis;

use App\Filament\Resources\Testimonis\Pages\ListTestimonis;
use App\Filament\Resources\Testimonis\Pages\ViewTestimoni;
use App\Filament\Resources\Testimonis\Tables\TestimonisTable;
use App\Models\Testimoni;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TestimoniResource extends Resource
{
    protected static ?string $model = Testimoni::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleBottomCenterText;

    protected static ?string $label = 'Testimoni';

    protected static ?string $navigationLabel = 'Testimoni';

    protected static string|UnitEnum|null $navigationGroup = 'Kelola';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        // Nonaktifkan form karena tidak boleh edit
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return TestimonisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTestimonis::route('/'),
            'view' => ViewTestimoni::route('/{record}'),
            // Hapus create dan edit routes
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Nonaktifkan pembuatan testimoni
    }
}
