<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Testimoni;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Enums\Status;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use App\Filament\Resources\Testimonis\Schemas\TestimoniInfolist;

class TestimoniActions extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Testimoni $testimoni;

    // Tambahkan properti untuk aksi
    protected function getActions(): array
    {
        return [
            Action::make('approve')
                ->label('Setujui')
                ->color('success')
                ->requiresConfirmation()
                ->action(fn() => $this->approve()),

            Action::make('reject')
                ->label('Tolak')
                ->color('danger')
                ->requiresConfirmation()
                ->action(fn() => $this->reject()),
        ];
    }

    public function mount(Testimoni $testimoni)
    {
        $this->testimoni = $testimoni;
    }

    public function approve()
    {
        $this->testimoni->update(['status' => Status::Disetujui]);

        Notification::make()
            ->title('Testimoni disetujui.')
            ->success()
            ->send();
    }

    public function reject()
    {
        $this->testimoni->update(['status' => Status::Ditolak]);

        Notification::make()
            ->title('Testimoni ditolak.')
            ->danger()
            ->send();
    }

    public function render()
    {
        return view('livewire.testimoni-actions');
    }
}
