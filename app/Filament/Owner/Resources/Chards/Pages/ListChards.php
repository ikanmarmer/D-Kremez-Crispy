<?php

namespace App\Filament\Owner\Resources\Chards\Pages;

use App\Filament\Owner\Resources\Chards\ChardResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListChards extends ListRecords
{
    protected static string $resource = ChardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
