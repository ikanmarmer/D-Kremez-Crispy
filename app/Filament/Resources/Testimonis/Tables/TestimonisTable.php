<?php

namespace App\Filament\Resources\Testimonis\Tables;

use App\Enums\Status;
use App\Models\Testimoni;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Filament\Resources\Testimonis\TestimoniResource;

class TestimonisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Daftar Testimoni')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Pengguna')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('content')
                    ->label('Isi Testimoni')
                    ->words(20)
                    ->wrap(),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->color(fn($state) => match (true) {
                        $state >= 4 => 'success',
                        $state >= 3 => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        Status::Menunggu->value => 'warning',
                        Status::Disetujui->value => 'success',
                        Status::Ditolak->value => 'danger',
                    }),
                TextColumn::make('created_at')
                    ->label('Dibuat pada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                // Filter berdasarkan status
            ])
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn(Testimoni $record): string => TestimoniResource::getUrl('view', ['record' => $record])),
                Action::make('approve')
                    ->label('Setujui')
                    ->visible(fn(Testimoni $record): bool => $record->status === Status::Menunggu->value)
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->action(function (Testimoni $record) {
                        $record->update(['status' => Status::Disetujui->value]);
                        Notification::make()
                            ->title('Testimoni disetujui.')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->visible(fn(Testimoni $record): bool => $record->status === Status::Menunggu->value)
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->action(function (Testimoni $record) {
                        $record->update(['status' => Status::Ditolak->value]);
                        Notification::make()
                            ->title('Testimoni ditolak.')
                            ->danger()
                            ->send();
                    }),
                Action::make('delete_rejected')
                    ->label('Hapus Testimoni Ditolak')
                    ->visible(fn(Testimoni $record): bool => $record->status === Status::Ditolak->value)
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->action(function (Testimoni $record) {
                        $record->delete();
                        Notification::make()
                            ->title('Testimoni ditolak dihapus.')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                // Tidak perlu bulk actions untuk menjaga keamanan data
            ])
            ->defaultSort('created_at', 'desc');
    }
}
