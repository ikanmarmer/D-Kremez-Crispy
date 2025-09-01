<?php

namespace App\Filament\Karyawan\Resources\LaporanPenjualans\Pages;

use App\Filament\Karyawan\Resources\LaporanPenjualans\LaporanPenjualanResource;
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
