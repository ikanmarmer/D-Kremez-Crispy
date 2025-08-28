<?php

namespace App\Filament\Resources\PergerakanStoks\Pages;

use App\Filament\Resources\PergerakanStoks\PergerakanStokResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPergerakanStoks extends ListRecords
{
    protected static string $resource = PergerakanStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
