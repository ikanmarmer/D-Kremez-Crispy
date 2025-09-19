<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Role;
use App\Enums\Status;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Filament\Support\Enums\FontWeight;
use Filament\Actions\Action;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // SECTION: Profil Pengguna
            Section::make('Profil Pengguna')
                ->description('Informasi dasar pengguna')
                ->icon('heroicon-o-user-circle')
                ->extraAttributes([
                    'class' => 'overflow-hidden bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950 dark:to-indigo-950 border-0 shadow-lg rounded-xl'
                ])
                ->schema([
                    Flex::make([
                        // Sub-section: Avatar
                        Section::make([
                            ImageEntry::make('avatar')
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

                        // Sub-section: Detail Profil
                        Section::make([
                            TextEntry::make('name')
                                ->label('Nama Lengkap')
                                ->size(TextSize::Large)
                                ->weight(FontWeight::Bold)
                                ->extraAttributes([
                                    'class' => 'text-2xl font-bold text-gray-900 dark:text-white mb-2'
                                ]),

                            TextEntry::make('email')
                                ->label('Alamat Email')
                                ->icon('heroicon-m-envelope')
                                ->copyable()
                                ->copyMessage('Email disalin!')
                                ->extraAttributes([
                                    'class' => 'text-base text-gray-600 dark:text-gray-300 mb-3'
                                ]),

                            TextEntry::make('role')
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

            // --- //

            // SECTION: Riwayat Waktu
            Section::make('Riwayat Waktu')
                ->description('Informasi penciptaan dan pembaruan akun')
                ->icon('heroicon-o-calendar-days')
                ->extraAttributes([
                    'class' => 'rounded-lg'
                ])
                ->columns([
                    'default' => 1,
                    'sm' => 2,
                ])
                ->schema([
                    TextEntry::make('created_at')
                        ->label('Akun Dibuat')
                        ->icon('heroicon-m-plus-circle') // ✅ New icon added
                        ->dateTime()
                        ->since()
                        ->tooltip(fn($state) => $state?->format('d M Y, H:i:s'))
                        ->extraAttributes(['class' => 'text-sm font-medium'])
                        ->columnSpan(1),

                    TextEntry::make('updated_at')
                        ->label('Terakhir Diperbarui')
                        ->icon('heroicon-m-pencil-square') // ✅ New icon added
                        ->dateTime()
                        ->since()
                        ->tooltip(fn($state) => $state?->format('d M Y, H:i:s'))
                        ->extraAttributes(['class' => 'text-sm font-medium'])
                        ->columnSpan(1),
                ]),

            // --- //

            // SECTION: Foto Testimoni
            Section::make('Foto Testimoni')
                ->icon('heroicon-o-photo') // ✅ Icon added
                ->schema([
                    ImageEntry::make('testimonial.product_photo')
                        ->label('Foto Testimoni')
                        ->disk('public')
                        ->placeholder('Tidak ada foto produk')
                        ->height(300)
                        ->extraImgAttributes([
                            'class' => 'w-full h-full max-w-md mx-auto rounded-lg object-cover shadow-md',
                            'alt' => 'Foto Produk',
                        ])
                        ->columnSpanFull(),
                ])
                ->visible(fn($record) => $record->testimonial()->exists()), // ✅ Tambahkan kondisi visibilitas

            // SECTION: Isi Testimoni
            Section::make('Isi Testimoni')
                ->icon('heroicon-o-chat-bubble-bottom-center-text') // ✅ Icon added
                ->schema([
                    TextEntry::make('testimonial.content')
                        ->label('Testimoni')
                        ->markdown()
                        ->prose()
                        ->columnSpanFull(),
                ])
                ->visible(fn($record) => $record->testimonial()->exists()), // ✅ Tambahkan kondisi visibilitas

            // SECTION: Detail Testimoni
            Section::make('Detail Testimoni')
                ->icon('heroicon-o-clipboard-document-list') // ✅ Icon added
                ->schema([
                    Grid::make()
                        ->schema([
                            TextEntry::make('testimonial.status')
                                ->label('Status')
                                ->badge()
                                ->color(fn(string $state): string => match ($state) {
                                    Status::Menunggu->value => 'warning',
                                    Status::Disetujui->value => 'success',
                                    Status::Ditolak->value => 'danger',
                                })
                                ->icon(fn(string $state): string => match ($state) { // ✅ Icons based on status
                                    Status::Menunggu->value => 'heroicon-m-clock',
                                    Status::Disetujui->value => 'heroicon-m-check-circle',
                                    Status::Ditolak->value => 'heroicon-m-x-circle',
                                }),

                            TextEntry::make('testimonial.rating')
                                ->label('Rating')
                                ->badge()
                                ->color('primary')
                                ->icon('heroicon-s-star')
                                ->formatStateUsing(fn($state) => "{$state}/5"),

                            TextEntry::make('testimonial.created_at')
                                ->label('Tanggal Dibuat')
                                ->dateTime()
                                ->icon('heroicon-o-calendar-days') // ✅ Icon added
                                ->placeholder('-'),

                            TextEntry::make('testimonial.updated_at')
                                ->label('Tanggal Diperbarui')
                                ->dateTime()
                                ->icon('heroicon-o-calendar-days') // ✅ Icon added
                                ->placeholder('-'),
                        ])
                        ->columns([
                            'default' => 2,
                            'md' => 3,
                            'lg' => 3,
                        ]),
                ])
                ->visible(fn($record) => $record->testimonial()->exists()), // ✅ Tambahkan kondisi visibilitas


                // SECTION: Aksi (Approve / Reject)
                Section::make('Aksi')
                    ->icon('heroicon-o-hand-thumb-up')
                    ->schema([
                        Action::make('approve')
                            ->label('Setujui')
                            ->color('success')
                            ->icon('heroicon-m-check-circle')
                            ->requiresConfirmation()
                            ->action(fn($record) => $record->update([
                                'status' => Status::Disetujui,
                            ]))
                            // ✅ Hanya tampilkan jika statusnya 'Menunggu'
                            ->visible(fn($record) => $record->status === Status::Menunggu),

                        Action::make('reject')
                            ->label('Tolak')
                            ->color('danger')
                            ->icon('heroicon-m-x-circle')
                            ->requiresConfirmation()
                            ->action(fn($record) => $record->update([
                                'status' => Status::Ditolak,
                            ]))
                            // ✅ Hanya tampilkan jika statusnya 'Menunggu'
                            ->visible(fn($record) => $record->status === Status::Menunggu),
                    ])
                    // ✅ Tambahkan kondisi visibilitas untuk Section
                    ->visible(fn($record) => $record->status === Status::Menunggu),
                            ]);

    }
}
