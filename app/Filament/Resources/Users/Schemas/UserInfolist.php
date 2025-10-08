<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Role;
use App\Enums\Status;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Filament\Support\Enums\FontWeight;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Support\Enums\Width;
use Illuminate\Support\HtmlString;

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
                    'class' => 'overflow-hidden bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950 dark:to-indigo-950 border-0 shadow-lg rounded-xl',
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
                                    'class' => 'ring-4 ring-white dark:ring-gray-800 shadow-xl cursor-pointer',
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
<div class='flex items-center justify-center w-full h-full'>
  <div class='relative bg-white rounded-lg shadow-xl overflow-hidden
              w-full max-w-[600px] aspect-square'>
    <div class='w-full h-full flex items-center justify-center bg-gray-100'>
      <img
        src='{$url}''
        alt='{$record->name} Avatar'
        class='w-full h-full object-contain p-2'
      />
    </div>
  </div>
</div>
        "
                                                : "
        <div class='text-center text-gray-400 py-8'>
            Belum ada avatar.
        </div>
        "
                                            );
                                        })
                                        ->modalWidth(Width::Large)
                                        ->closeModalByClickingAway()
                                        ->modalSubmitAction(false)
                                        ->modalCancelAction(false)
                                )
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
                                    'class' => 'text-2xl font-bold text-gray-900 dark:text-white mb-2',
                                ]),

                            TextEntry::make('email')
                                ->label('Alamat Email')
                                ->icon('heroicon-m-envelope')
                                ->copyable()
                                ->copyMessage('Email disalin!')
                                ->extraAttributes([
                                    'class' => 'text-sm font-medium text-gray-700 dark:text-gray-200',
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

            // SECTION: Riwayat Waktu
            Section::make('Riwayat Waktu')
                ->description('Informasi penciptaan dan pembaruan akun')
                ->icon('heroicon-o-calendar-days')
                ->extraAttributes([
                    'class' => 'rounded-lg',
                ])
                ->columns([
                    'default' => 1,
                    'sm' => 2,
                ])
                ->schema([
                    TextEntry::make('created_at')
                        ->label('Akun Dibuat')
                        ->icon('heroicon-m-plus-circle')
                        ->dateTime()
                        ->since()
                        ->tooltip(fn($state) => $state?->format('d M Y, H:i:s'))
                        ->extraAttributes(['class' => 'text-sm font-medium'])
                        ->columnSpan(1),

                    TextEntry::make('updated_at')
                        ->label('Terakhir Diperbarui')
                        ->icon('heroicon-m-pencil-square')
                        ->dateTime()
                        ->since()
                        ->tooltip(fn($state) => $state?->format('d M Y, H:i:s'))
                        ->extraAttributes(['class' => 'text-sm font-medium'])
                        ->columnSpan(1),
                ]),

            // SECTION: Foto Produk
            Section::make('Foto Produk')
                ->icon('heroicon-o-photo')
                ->hidden(fn($record) => $record->testimonial === null)
                ->schema([
                    ImageEntry::make('testimonial.product_photo')
                        ->disk('public')
                        ->label('Foto Produk')
                        ->placeholder('Tidak ada foto produk')
                        ->height(300)
                        ->extraImgAttributes([
                            'class' => 'w-full h-full max-w-md mx-auto rounded-lg object-cover shadow-md cursor-pointer',
                        ])
                        ->action(
                            Action::make('previewProductPhoto')
                                ->label('Preview Foto Produk')
                                ->modalHeading('Preview Foto Produk')
                                ->modalContent(function ($record) {
                                    $url = $record->avatar
                                        ? asset('storage/' . $record->avatar)
                                        : null;

                                    return new HtmlString(
                                        $url
                                        ? "
        <div class='flex items-center justify-center w-full h-full p-4'>
          <div class='relative bg-white rounded-lg shadow-lg overflow-hidden
                      w-full max-w-[600px]'>
            <div class='w-full aspect-square flex items-center justify-center bg-gray-50'>
              <img
                src='{$url}'
                alt='{$record->name} Avatar'
                class='w-full h-full object-contain'
                style='max-width: 100%; max-height: 100%;'
              />
            </div>
          </div>
        </div>
        "
                                        : "
        <div class='text-center text-gray-400 py-8'>
            Belum ada avatar.
        </div>
        "
                                    );
                                })
                                ->modalWidth(Width::ExtraLarge)
                                ->closeModalByClickingAway()
                                ->modalSubmitAction(false)
                                ->modalCancelAction(false)
                        )
                        ->columnSpanFull(),
                ]),

            // Section: Testimoni
            Section::make('Detail Testimoni')
                ->icon('heroicon-o-clipboard-document-list')
                ->description('Status dan informasi teknis testimoni')
                ->schema([
                    Grid::make()
                        ->schema([
                            TextEntry::make('testimonial.status')
                                ->label('Status')
                                ->badge()
                                ->icon(fn($state): string => match (
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

                            TextEntry::make('testimonial.rating')
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

                            IconEntry::make('testimonial.is_notified')
                                ->label('Sudah Dilihat Pengguna?')
                                ->boolean(),

                            TextEntry::make('testimonial.created_at')
                                ->label('Tanggal Dibuat')
                                ->dateTime()
                                ->icon('heroicon-m-plus-circle')
                                ->placeholder('-'),

                            TextEntry::make('testimonial.updated_at')
                                ->label('Tanggal Diperbarui')
                                ->dateTime()
                                ->icon('heroicon-m-pencil-square')
                                ->placeholder('-'),
                        ])
                        ->columns([
                            'default' => 1,
                            'md' => 2,
                            'lg' => 3,
                        ])
                ])
                ->visible(fn($record) => $record->testimonial?->status === 'Menunggu'),

            // Section: Isi Testimoni
            Section::make('Isi Testimoni')
                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                ->collapsible()
                ->hidden(fn($record) => $record->testimonial === null)
                ->schema([
                    TextEntry::make('testimonial.content')
                        ->label('Testimoni')
                        ->markdown()
                        ->prose()
                        ->columnSpanFull(),
                ]),

            // Section: Moderasi
            Section::make('Moderasi')
                ->icon('heroicon-o-adjustments-horizontal')
                ->description('Pilih tindakan untuk testimoni ini.')
                ->schema([
                    Flex::make([
                        Action::make('approve')
                            ->label('Setujui')
                            ->color('success')
                            ->icon('heroicon-m-check-circle')
                            ->requiresConfirmation()
                            ->action(function ($record) {
                                $record->testimonial?->update([
                                    'status' => Status::Disetujui,
                                ]);

                                Notification::make()
                                    ->title('Testimoni berhasil disetujui.')
                                    ->success()
                                    ->send();
                            }),

                        Action::make('reject')
                            ->label('Tolak')
                            ->color('danger')
                            ->icon('heroicon-m-x-circle')
                            ->requiresConfirmation()
                            ->action(function ($record) {
                                $record->testimonial?->update([
                                    'status' => Status::Ditolak,
                                ]);

                                Notification::make()
                                    ->title('Testimoni berhasil ditolak.')
                                    ->danger()
                                    ->send();
                            }),
                    ])->gap(3),
                ])
                ->columns(2)
                ->visible(fn($record) => $record->testimonial?->status === 'Menunggu'),
        ]);
    }
}
