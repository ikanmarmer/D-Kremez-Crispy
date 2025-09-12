<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Role;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('email')
                    ->label('Alamat email')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->label('Password')
                    ->revealable()
                    ->password()
                    ->required(fn (string $context): bool => $context === 'create')
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn (string $context): bool => $context === 'create')
                    ->nullable(fn (string $context): bool => $context === 'edit')
                    ->minLength(8),
                Select::make('role')
                    ->label('Role')
                    ->placeholder('Pilih role')
                    ->options(options: [
                        'User' => Role::User->value,
                        'Admin' => Role::Admin->value,
                        'Karyawan' => Role::Karyawan->value,
                    ])
                    ->required(),
                FileUpload::make('avatar')
                    ->label('Foto Profil')
                    ->image()
                    ->disk('public')
                    ->directory('avatars')
                    ->nullable(),
            ]);
    }
}
