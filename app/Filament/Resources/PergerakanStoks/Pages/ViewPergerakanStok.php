<?php

namespace App\Filament\Resources\PergerakanStoks\Pages;

use App\Filament\Resources\PergerakanStoks\PergerakanStokResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPergerakanStok extends ViewRecord
{
    protected static string $resource = PergerakanStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
