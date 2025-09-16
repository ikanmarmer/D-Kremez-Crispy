<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Status;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        Fieldset::make('Informasi Pengguna')
                            ->schema([
                                TextEntry::make('name'),
                                TextEntry::make('email')
                                    ->label('Alamat email'),
                                TextEntry::make('role')
                                    ->label('Peran'),
                                ImageEntry::make('avatar')
                                    ->label('Avatar')
                                    ->disk('public')
                                    ->placeholder('Belum ada avatar')
                                    ->circular()
                                    ->height(100),
                                TextEntry::make('created_at')
                                    ->label('Dibuat pada')
                                    ->dateTime(),
                                TextEntry::make('updated_at')
                                    ->label('Diperbarui pada')
                                    ->dateTime(),
                            ]),
                        Fieldset::make('Testimoni')
                            ->schema([
                                TextEntry::make('testimonial.content')
                                    ->label('Isi Testimoni')
                                    ->placeholder('Belum ada testimoni')
                                    ->html()
                                    ->columnSpanFull(),
                                TextEntry::make('testimonial.rating')
                                    ->label('Rating')
                                    ->placeholder('Belum ada rating')
                                    ->badge()
                                    ->color(fn($state) => $state ? 'primary' : 'gray'),
                                TextEntry::make('testimonial.status')
                                    ->label('Status')
                                    ->placeholder('Belum ada testimoni')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        Status::Menunggu->value => 'warning',
                                        Status::Disetujui->value => 'success',
                                        Status::Ditolak->value => 'danger',
                                        default => 'gray',
                                    }),
                                ImageEntry::make('testimonial.product_photo')
                                    ->label('Foto Produk')
                                    ->disk('public')
                                    ->placeholder('Belum ada foto produk')
                                    ->visible(fn($record) => $record->testimonial && $record->testimonial->product_photo),
                            ])
                            ->headerActions([
                                Actions::make([
                                    Action::make('approve_testimonial')
                                        ->label('Setujui Testimoni')
                                        ->color('success')
                                        ->icon('heroicon-o-check-circle')
                                        ->visible(fn($record) => $record->testimonial && $record->testimonial->status === Status::Menunggu->value)
                                        ->action(function ($record) {
                                            $record->testimonial->update(['status' => Status::Disetujui->value]);
                                            Notification::make()
                                                ->title('Testimoni disetujui!')
                                                ->success()
                                                ->send();
                                        }),
                                    Action::make('reject_testimonial')
                                        ->label('Tolak Testimoni')
                                        ->color('danger')
                                        ->icon('heroicon-o-x-circle')
                                        ->visible(fn($record) => $record->testimonial && $record->testimonial->status === Status::Menunggu->value)
                                        ->action(function ($record) {
                                            $record->testimonial->update(['status' => Status::Ditolak->value]);
                                            Notification::make()
                                                ->title('Testimoni ditolak!')
                                                ->danger()
                                                ->send();
                                        }),
                                    Action::make('delete_rejected_testimonial')
                                        ->label('Hapus Testimoni Ditolak')
                                        ->color('danger')
                                        ->icon('heroicon-o-trash')
                                        ->visible(fn($record) => $record->testimonial && $record->testimonial->status === Status::Ditolak->value)
                                        ->requiresConfirmation()
                                        ->action(function ($record) {
                                            $record->testimonial->delete();
                                            Notification::make()
                                                ->title('Testimoni ditolak dihapus!')
                                                ->success()
                                                ->send();
                                        }),
                                ]),
                            ]),
                    ]),

            ]);
    }
}
