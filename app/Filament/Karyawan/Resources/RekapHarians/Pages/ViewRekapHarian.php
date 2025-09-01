<?php

namespace App\Filament\Karyawan\Resources\RekapHarians\Pages;

use App\Filament\Karyawan\Resources\RekapHarians\RekapHarianResource;
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
