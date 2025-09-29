<?php

namespace App\Filament\Karyawan\Resources\StokMentahs\Pages;

use App\Filament\Karyawan\Resources\StokMentahs\StokMentahResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditStokMentah extends EditRecord
{
    protected static string $resource = StokMentahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
