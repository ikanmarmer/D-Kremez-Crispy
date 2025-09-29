<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Illuminate\Support\HtmlString;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Table;
use App\Enums\Role;

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
                    ->height(70)
                    ->extraImgAttributes([
                        'class' => 'cursor-pointer rounded-lg shadow-md w-full object-contain',
                    ])
                    ->action(
                        Action::make('previewAvatar')
                            ->label('Preview Avatar')
                            ->modalHeading('Preview Avatar Pengguna')
                            ->modalContent(function ($record) {
                                $url = $record->avatar
                                    ? asset('storage/' . $record->avatar)
                                    : null;

                                return new HtmlString(
                                    $url
                                    ? "
                        <div class='flex flex-col items-center space-y-4'>
                            <img
                                src='{$url}'
                                alt='{$record->name} Avatar'
                                class='max-h-[80vh] w-auto rounded-xl shadow-lg object-contain cursor-zoom-in'
                                onclick='this.classList.toggle(\"scale-150\")'
                            >
                        </div>
                    "
                                    : "
                        <div class='text-gray-400'>
                            Belum ada avatar.
                        </div>
                    "
                                );
                            })
                            ->modalWidth('7xl')
                            ->closeModalByClickingAway()
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false)
                    ),

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
                    ->limit(30),

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
                MultiSelectFilter::make('role')
                    ->label('Filter Peran')
                    ->options([
                        Role::Admin->value => 'Admin',
                        Role::User->value => 'User',
                        Role::Karyawan->value => 'Karyawan',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->label('Lihat'),

                    EditAction::make()
                        ->label('Edit')
                        ->hidden(fn($record) => $record->role === Role::User),

                    DeleteAction::make()
                        ->label('Hapus')
                        ->hidden(fn($record) => $record->role === Role::User)
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Pengguna')
                        ->modalSubheading('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.'),
                ])
                    ->color('gray')
                    ->hidden(fn($record) => $record->role === Role::User), // sembunyikan grup untuk User
                ViewAction::make()->label('Lihat')->visible(fn($record) => $record->role === Role::User), // User tetap bisa lihat
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Pengguna')
                        ->modalSubheading('Apakah Anda yakin ingin menghapus pengguna yang dipilih? Tindakan ini tidak dapat dibatalkan.'),
                ]),
            ])
            ->selectCurrentPageOnly()
            ->checkIfRecordIsSelectableUsing(
                fn($record) => $record->role !== Role::User
            );

    }
}
