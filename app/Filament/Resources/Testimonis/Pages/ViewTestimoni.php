<?php

namespace App\Filament\Resources\Testimonis\Pages;

use App\Filament\Resources\Testimonis\TestimoniResource;
use App\Models\Testimoni;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Enums\Status;

class ViewTestimoni extends ViewRecord
{
    protected static string $resource = TestimoniResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
            Action::make('delete')
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
                    $this->redirect(TestimoniResource::getUrl('index'));
                }),
        ];
    }
}