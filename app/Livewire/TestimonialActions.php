<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Testimoni;
use Filament\Notifications\Notification;
use App\Filament\Resources\Testimonis\Schemas\TestimoniInfolist;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class TestimonialActions extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public Testimoni $testimoni;

    public function mount(Testimoni $testimoni)
    {
        $this->testimoni = $testimoni;
    }

    public function approve()
    {
        $this->testimoni->update(['status' => 'disetujui']);

        Notification::make()
            ->title('Testimoni disetujui.')
            ->success()
            ->send();
    }

    public function reject()
    {
        $this->testimoni->update(['status' => 'ditolak']);

        Notification::make()
            ->title('Testimoni ditolak.')
            ->danger()
            ->send();
    }

    protected function getInfolistSchema(): array
    {
        // Re-use your existing infolist schema
        return TestimoniInfolist::getSchema();
    }

    public function render()
    {
        return view('livewire.testimonial-actions');
    }
}
