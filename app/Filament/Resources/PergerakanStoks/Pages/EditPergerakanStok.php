<?php

namespace App\Filament\Resources\PergerakanStoks\Pages;

use App\Filament\Resources\PergerakanStoks\PergerakanStokResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPergerakanStok extends EditRecord
{
    protected static string $resource = PergerakanStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
