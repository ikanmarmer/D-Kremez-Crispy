<?php

namespace App\Filament\Owner\Resources\RekapHarians\Pages;

use App\Filament\Owner\Resources\RekapHarians\RekapHarianResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRekapHarian extends ViewRecord
{
    protected static string $resource = RekapHarianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
