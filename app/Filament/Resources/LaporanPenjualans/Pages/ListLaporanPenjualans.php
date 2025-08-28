<?php

namespace App\Filament\Resources\LaporanPenjualans\Pages;

use App\Filament\Resources\LaporanPenjualans\LaporanPenjualanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLaporanPenjualans extends ListRecords
{
    protected static string $resource = LaporanPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
