<?php

namespace App\Filament\Karyawan\Resources\Produks\Pages;

use App\Filament\Karyawan\Resources\Produks\ProdukResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProduk extends ViewRecord
{
    protected static string $resource = ProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
