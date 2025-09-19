<?php

namespace App\Filament\Resources\Testimonis\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TestimoniForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('avatar')
                    ->default(null),
                TextInput::make('rating')
                    ->required()
                    ->numeric(),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('product_photo')
                    ->default(null),
                TextInput::make('status')
                    ->required()
                    ->default('Menunggu'),
                Toggle::make('is_notified')
                    ->required(),
            ]);
    }
}
