<?php

namespace App\Filament\Karyawan\Resources\StokMentahs\Pages;

use App\Filament\Karyawan\Resources\StokMentahs\StokMentahResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStokMentahs extends ListRecords
{
    protected static string $resource = StokMentahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
