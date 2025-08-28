<?php

namespace App\Filament\Resources\PengaturanTampilans\Pages;

use App\Filament\Resources\PengaturanTampilans\PengaturanTampilanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPengaturanTampilan extends ViewRecord
{
    protected static string $resource = PengaturanTampilanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
