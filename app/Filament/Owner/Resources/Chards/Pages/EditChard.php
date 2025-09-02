<?php

namespace App\Filament\Owner\Resources\Chards\Pages;

use App\Filament\Owner\Resources\Chards\ChardResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditChard extends EditRecord
{
    protected static string $resource = ChardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
