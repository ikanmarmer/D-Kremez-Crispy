<?php

namespace App\Filament\Resources\DetailLaporanPenjualans\Pages;

use App\Filament\Resources\DetailLaporanPenjualans\DetailLaporanPenjualanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDetailLaporanPenjualans extends ListRecords
{
    protected static string $resource = DetailLaporanPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
