<?php

namespace App\Filament\Resources\Testimonis\Tables;

use Filament\Tables\Table;
use App\Models\Testimoni;
use App\Enums\Status;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;

class TestimonisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Daftar Testimoni')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Pengguna'),
                TextColumn::make('konten')
                    ->label('Isi Testimoni')
                    ->words(50)
                    ->wrap(),
                TextColumn::make('penilaian')
                    ->label('Rating')
                    ->badge(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Status::Menunggu->value => 'warning',
                        Status::Disetujui->value => 'success',
                        Status::Ditolak->value => 'danger',
                    }),
                TextColumn::make('created_at')
                    ->label('Dibuat pada')
                    ->dateTime(),
            ])
            ->headerActions([ // Gunakan headerActions() untuk menambahkan tombol
                CreateAction::make(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                //
            ])
            ->actions([
                Action::make('approve')
                ->label('Setujui')
                ->visible(fn (Testimoni $record): bool => $record->status === Status::Menunggu->value)
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
                    ->visible(fn (Testimoni $record): bool => $record->status === Status::Menunggu->value)
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->action(function (Testimoni $record) {
                        $record->update(['status' => Status::Ditolak->value]);
                        Notification::make()
                            ->title('Testimoni ditolak.')
                            ->danger()
                            ->send();
                }),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
