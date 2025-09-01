<?php

namespace App\Filament\Karyawan\Resources\RekapHarians\Pages;

use App\Filament\Karyawan\Resources\RekapHarians\RekapHarianResource;
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
