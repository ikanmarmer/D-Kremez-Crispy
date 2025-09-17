<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Role;
use App\Filament\Resources\Testimonis\Schemas\TestimoniInfolist;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Profil Pengguna')
                ->columns(2)
                ->components([
                    ImageEntry::make('avatar')
                        ->label('Avatar')
                        ->disk('public')
                        ->placeholder('Belum ada avatar')
                        ->extraImgAttributes([
                            'class' => 'w-full h-full rounded-lg object-cover shadow-md',
                        ])
                        ->columnSpan(1),
                    Group::make([
                        TextEntry::make('name')
                            ->label('Nama Lengkap')
                            ->extraAttributes(['class' => 'text-lg font-semibold']),
                        TextEntry::make('email')
                            ->label('Alamat Email')
                            ->copyable()
                            ->extraAttributes(['class' => 'text-sm font-semibold']),
                        TextEntry::make('role')
                            ->label('Peran')
                            ->badge()
                            ->formatStateUsing(fn(Role $state) => match ($state) {
                                Role::Admin => 'Admin',
                                Role::User => 'Pengguna',
                                Role::Karyawan => 'Karyawan',
                            })
                            ->color(fn(Role $state): string => match ($state) {
                                Role::Admin => 'secondary',
                                Role::User => 'success',
                                Role::Karyawan => 'primary',
                            }),
                    ])->columnSpan(1),
                ]),

            Section::make('Waktu')
                ->columns(2)
                ->components([
                    TextEntry::make('created_at')
                        ->label('Dibuat pada')
                        ->dateTime()
                        ->extraAttributes(['class' => 'text-sm text-gray-500']),
                    TextEntry::make('updated_at')
                        ->label('Diperbarui pada')
                        ->dateTime()
                        ->extraAttributes(['class' => 'text-sm text-gray-500']),
                ]),
        ]);
    }
}
