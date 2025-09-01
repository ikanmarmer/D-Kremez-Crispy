<?php

namespace App\Filament\Karyawan\Resources\RekapHarians\Pages;

use App\Filament\Karyawan\Resources\RekapHarians\RekapHarianResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRekapHarian extends EditRecord
{
    protected static string $resource = RekapHarianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
