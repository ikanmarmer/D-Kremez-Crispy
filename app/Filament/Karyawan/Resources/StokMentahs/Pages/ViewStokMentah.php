<?php

namespace App\Filament\Karyawan\Resources\StokMentahs\Pages;

use App\Filament\Karyawan\Resources\StokMentahs\StokMentahResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStokMentah extends ViewRecord
{
    protected static string $resource = StokMentahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
