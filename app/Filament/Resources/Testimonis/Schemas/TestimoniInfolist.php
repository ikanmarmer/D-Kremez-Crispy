<?php

namespace App\Filament\Resources\Testimonis\Schemas;

use App\Enums\Status;
use App\Models\Testimoni;
use Filament\Actions\Action as InfolistAction;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

class TestimoniInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // SECTION: Informasi Pengguna
                Section::make('Informasi Pengguna')
                    ->schema([
                        Grid::make()
                            ->schema([
                                ImageEntry::make('user.avatar')
                                    ->disk('public')
                                    ->label('Avatar')
                                    ->placeholder('Tidak ada avatar')
                                    ->extraImgAttributes([
                                        'class' => 'w-32 h-32 rounded-full object-cover shadow-md mx-auto',
                                        'alt' => 'Avatar Pengguna',
                                    ])
                                    ->columnSpanFull(),

                                TextEntry::make('user.name')
                                    ->label('Nama Pengguna')
                                    ->size(TextSize::Large)
                                    ->weight(FontWeight::Bold)
                                    ->icon('heroicon-o-user-circle')
                                    ->placeholder('-'),

                                TextEntry::make('user.email')
                                    ->label('Email')
                                    ->icon('heroicon-o-envelope')
                                    ->placeholder('-'),

                                TextEntry::make('user.role')
                                    ->label('Role')
                                    ->badge()
                                    ->color('info'),
                            ])
                            ->columns([
                                'default' => 1,
                                'md' => 3,
                            ]),
                    ]),

                // SECTION: Detail Testimoni
                Section::make('Detail Testimoni')
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        Status::Menunggu->value => 'warning',
                                        Status::Disetujui->value => 'success',
                                        Status::Ditolak->value => 'danger',
                                    }),

                                TextEntry::make('rating')
                                    ->label('Rating')
                                    ->badge()
                                    ->color('primary')
                                    ->icon('heroicon-s-star')
                                    ->formatStateUsing(fn($state) => "{$state}/5"),

                                IconEntry::make('is_notified')
                                    ->label('Sudah Dilihat Pengguna?')
                                    ->boolean(),

                                TextEntry::make('created_at')
                                    ->label('Tanggal Dibuat')
                                    ->dateTime()
                                    ->icon('heroicon-o-calendar-days')
                                    ->placeholder('-'),

                                TextEntry::make('updated_at')
                                    ->label('Tanggal Diperbarui')
                                    ->dateTime()
                                    ->icon('heroicon-o-calendar-days')
                                    ->placeholder('-'),
                            ])
                            ->columns([
                                'default' => 1,
                                'md' => 2,
                                'lg' => 3,
                            ]),
                    ]),

                // SECTION: Isi Testimoni
                Section::make('Isi Testimoni')
                    ->schema([
                        TextEntry::make('content')
                            ->label('')
                            ->markdown()
                            ->prose()
                            ->columnSpanFull(),
                    ]),

                // SECTION: Foto Produk
                Section::make('Foto Produk')
                    ->schema([
                        ImageEntry::make('product_photo')
                            ->disk('public')
                            ->label('')
                            ->placeholder('Tidak ada foto produk')
                            ->height(300)
                            ->extraImgAttributes([
                                'class' => 'w-full h-full max-w-md mx-auto rounded-lg object-cover shadow-md',
                                'alt' => 'Foto Produk',
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
