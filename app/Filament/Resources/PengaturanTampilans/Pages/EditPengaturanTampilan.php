<?php

namespace App\Filament\Resources\PengaturanTampilans\Pages;

use App\Filament\Resources\PengaturanTampilans\PengaturanTampilanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPengaturanTampilan extends EditRecord
{
    protected static string $resource = PengaturanTampilanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
