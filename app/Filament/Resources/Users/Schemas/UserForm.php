<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Role;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([

                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->validationAttribute('Nama Lengkap')
                    ->maxLength(255)
                    ->autocomplete('name'),

                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required()
                    ->validationAttribute('Alamat Email')
                    ->unique(ignoreRecord: true)
                    ->autocomplete('email'),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->validationAttribute('Password')
                    ->dehydrateStateUsing(
                        fn(?string $state): ?string =>
                        filled($state) ? Hash::make($state) : null
                    )
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->rule(Password::default())
                    ->placeholder(
                        fn(string $operation): string =>
                        $operation === 'create'
                        ? 'Masukkan password baru'
                        : 'Kosongkan jika tidak ingin mengubah'
                    ),

                Select::make('role')
                    ->label('Role')
                    ->placeholder('Pilih role')
                    ->options([
                        Role::User->value => 'User',
                        Role::Karyawan->value => 'Karyawan',
                        Role::Admin->value => 'Admin',
                    ])
                    ->required()
                    ->validationAttribute('Role')
                    ->searchable()
                    ->preload(),
                    
                FileUpload::make('avatar')
                    ->label('Foto Profil')
                    ->image()
                    ->disk('public')
                    ->directory('avatars')
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->helperText('Max 2MB, JPG/PNG/WebP')
                    ->validationAttribute('Foto Profil'),
            ]); // atur responsif kolom
    }
}
