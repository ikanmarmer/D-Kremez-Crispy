<?php

namespace App\Filament\Resources\Testimonis\Tables;

use App\Enums\Status;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use App\Models\Testimoni;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;


class TestimonisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Pengguna')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('content')
                    ->label('Isi Testimoni')
                    ->limit(100)
                    ->wrap(),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->icon(fn(int $state): string => match (true) {
                        $state >= 1 && $state <= 5 => 'heroicon-s-star',
                        default => 'heroicon-o-star'
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        Status::Menunggu->value => 'warning',
                        Status::Disetujui->value => 'success',
                        Status::Ditolak->value => 'danger',
                    })
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()
                    ->label('Lihat'),
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Testimoni ?')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui testimoni ini?')
                    ->action(function (Testimoni $record) {
                        $record->update([
                            'status' => Status::Disetujui,
                            'is_notified' => false,
                        ]);

                        Notification::make()
                            ->title('Testimoni disetujui')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Testimoni')
                    ->modalDescription('Apakah Anda yakin ingin menolak testimoni ini?')
                    ->action(function (Testimoni $record) {
                        $record->update([
                            'status' => Status::Ditolak,
                            'is_notified' => false,
                        ]);

                        Notification::make()
                            ->title('Testimoni ditolak')
                            ->danger()
                            ->send();
                    }),

                DeleteAction::make()
                    ->visible(fn(Testimoni $record): bool => $record->status === Status::Ditolak),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn(Collection $records): bool => $records->every(fn(Testimoni $record): bool => $record->status === Status::Ditolak)),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->selectCurrentPageOnly();
    }
}
