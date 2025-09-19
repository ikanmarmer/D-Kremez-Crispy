<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Table;
use app\Enums\Role;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Avatar
                ImageColumn::make('avatar')
                    ->label('Avatar')
                    ->disk('public')
                    ->extraImgAttributes([
                        'class' => 'w-full h-full rounded-lg object-cover shadow-md', 
                        'alt' => 'Avatar Pengguna',
                    ])
                    ->height(100)
                    ->placeholder('Belum ada avatar'),

                // Nama lengkap
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->weight('medium'),

                // Email
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->limit(30)
                    ->tooltip(fn($state) => $state),

                // Role
                TextColumn::make('role')
                    ->label('Peran')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state->value)
                    ->color(fn(Role $state): string => match ($state) {
                        Role::Admin => 'secondary',
                        Role::User => 'success',
                        Role::Karyawan => 'primary',
                        default => 'gray',
                    }),

                // Verifikasi email
                TextColumn::make('email_verified_at')
                    ->label('Email Diverifikasi')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Dibuat pada
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Diperbarui pada
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter berdasarkan Role
                MultiSelectFilter::make('role')
                    ->label('Filter Peran')
                    ->options([
                        Role::Admin->value => 'Admin',
                        Role::User->value => 'User',
                        Role::Karyawan->value => 'Karyawan',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()->label('Lihat'),
                EditAction::make()->label('Edit'),
                DeleteAction::make()->label('Hapus'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->selectCurrentPageOnly();
    }
}