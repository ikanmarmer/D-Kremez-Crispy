<?php

namespace App\Filament\Resources\Testimonis\Schemas;

use App\Enums\Status;
use App\Enums\Role;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Flex;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Actions\Action;

class TestimoniInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // SECTION: Informasi Pengguna
                Section::make('Informasi Pengguna')
                    ->icon('heroicon-o-user-circle')
                    ->description('Detail pengguna yang memberikan testimoni')
                    ->schema([
                        Flex::make([
                            Section::make([
                                ImageEntry::make('user.avatar')
                                    ->label('')
                                    ->disk('public')
                                    ->placeholder('Belum ada avatar')
                                    ->circular()
                                    ->size(120)
                                    ->extraImgAttributes([
                                        'class' => 'ring-4 ring-white dark:ring-gray-800 shadow-xl',
                                    ]),
                            ])
                                ->extraAttributes(['class' => 'flex justify-center items-center'])
                                ->grow(false),

                            Section::make([
                                TextEntry::make('user.name')
                                    ->label('Nama Lengkap')
                                    ->size(TextSize::Large)
                                    ->weight(FontWeight::Bold)
                                    ->extraAttributes([
                                        'class' => 'text-2xl font-bold text-gray-900 dark:text-white mb-2'
                                    ]),

                                TextEntry::make('user.email')
                                    ->label('Alamat Email')
                                    ->icon('heroicon-m-envelope')
                                    ->copyable()
                                    ->copyMessage('Email disalin!')
                                    ->extraAttributes([
                                        'class' => 'text-base text-gray-600 dark:text-gray-300 mb-3'
                                    ]),

                                TextEntry::make('user.role')
                                    ->label('Peran')
                                    ->badge()
                                    ->size('lg')
                                    ->formatStateUsing(fn(Role $state) => match ($state) {
                                        Role::Admin => 'Administrator',
                                        Role::User => 'Pengguna',
                                        Role::Karyawan => 'Karyawan',
                                    })
                                    ->color(fn(Role $state): string => match ($state) {
                                        Role::Admin => 'secondary',
                                        Role::User => 'success',
                                        Role::Karyawan => 'primary',
                                    })
                                    ->icon(fn(Role $state): string => match ($state) {
                                        Role::Admin => 'heroicon-m-shield-check',
                                        Role::User => 'heroicon-m-user',
                                        Role::Karyawan => 'heroicon-m-briefcase',
                                    }),
                            ])
                                ->extraAttributes(['class' => 'flex-1 space-y-3'])
                        ])
                            ->from('md'),
                    ]),

                // SECTION: Detail Testimoni
                Section::make('Detail Testimoni')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->description('Status dan informasi teknis testimoni')
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    // handle enum or string safely
                                    ->icon(fn($state): string => match (
                                    // normalize to string value
                                    $state instanceof Status ? $state->value : (string) $state
                                ) {
                                        Status::Menunggu->value => 'heroicon-m-clock',
                                        Status::Disetujui->value => 'heroicon-m-check-circle',
                                        Status::Ditolak->value => 'heroicon-m-x-circle',
                                        default => 'heroicon-m-question-mark-circle',
                                    })
                                    ->color(fn($state): string => match (
                                    $state instanceof Status ? $state->value : (string) $state
                                ) {
                                        Status::Menunggu->value => 'warning',
                                        Status::Disetujui->value => 'success',
                                        Status::Ditolak->value => 'danger',
                                        default => 'secondary',
                                    }),

                                TextEntry::make('rating')
                                    ->label('Rating')
                                    ->numeric()
                                    ->badge()
                                    ->color(fn(int $state): string => match (true) {
                                        $state <= 2 => 'danger',
                                        $state <= 3 => 'warning',
                                        $state <= 4 => 'info',
                                        $state == 5 => 'success',
                                        default => 'gray',
                                    })
                                    ->icon(fn(int $state): string => match (true) {
                                        $state >= 1 && $state <= 5 => 'heroicon-s-star',
                                        default => 'heroicon-o-star',
                                    }),

                                IconEntry::make('is_notified')
                                    ->label('Sudah Dilihat Pengguna?')
                                    ->boolean(),

                                TextEntry::make('created_at')
                                    ->label('Tanggal Dibuat')
                                    ->dateTime()
                                    ->icon('heroicon-m-plus-circle')
                                    ->placeholder('-'),

                                TextEntry::make('updated_at')
                                    ->label('Tanggal Diperbarui')
                                    ->dateTime()
                                    ->icon('heroicon-m-pencil-square')
                                    ->placeholder('-'),
                            ])
                            ->columns([
                                'default' => 1,
                                'md' => 2,
                                'lg' => 3,
                            ]),
                    ]),

                // SECTION: Foto Produk
                Section::make('Foto Produk')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        ImageEntry::make('product_photo')
                            ->disk('public')
                            ->label('Foto Produk')
                            ->placeholder('Tidak ada foto produk')
                            ->height(300)
                            ->extraImgAttributes([
                                'class' => 'w-full h-full max-w-md mx-auto rounded-lg object-cover shadow-md',
                                'alt' => 'Foto Produk',
                            ])
                            ->columnSpanFull(),
                    ]),

                // SECTION: Isi Testimoni
                Section::make('Isi Testimoni')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->schema([
                        TextEntry::make('content')
                            ->label('Testimoni')
                            ->markdown()
                            ->prose()
                            ->columnSpanFull(),
                    ]),

                Section::make('Aksi Moderasi')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->description('Pilih tindakan untuk testimoni ini.')
                    ->schema([
                        Flex::make([
                            Action::make('approve')
                                ->label('Setujui')
                                ->color('success')
                                ->icon('heroicon-m-check-circle')
                                ->requiresConfirmation()
                                ->action(fn($record) => $record->update([
                                    'status' => Status::Disetujui,
                                ])),

                                
                            Action::make('reject')
                                ->label('Tolak')
                                ->color('danger')
                                ->icon('heroicon-m-x-circle')
                                ->requiresConfirmation()
                                ->action(fn($record) => $record->update([
                                    'status' => Status::Ditolak,
                                ])),

                        ])->gap(3), // ✅ jarak antar tombol
                    ])
                    ->columns(2)
                    ->hidden(fn($record) => in_array($record->status, [Status::Disetujui, Status::Ditolak])),
            ]);
    }
}
