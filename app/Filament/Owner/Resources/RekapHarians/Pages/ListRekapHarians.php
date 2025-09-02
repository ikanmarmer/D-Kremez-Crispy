<?php

namespace App\Filament\Owner\Resources\RekapHarians\Pages;

use App\Filament\Owner\Resources\RekapHarians\RekapHarianResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRekapHarians extends ListRecords
{
    protected static string $resource = RekapHarianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
