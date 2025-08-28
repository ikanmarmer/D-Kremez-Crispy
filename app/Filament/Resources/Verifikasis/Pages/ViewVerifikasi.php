<?php

namespace App\Filament\Resources\Verifikasis\Pages;

use App\Filament\Resources\Verifikasis\VerifikasiResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVerifikasi extends ViewRecord
{
    protected static string $resource = VerifikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
