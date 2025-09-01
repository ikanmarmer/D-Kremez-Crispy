<?php

namespace App\Filament\Karyawan\Resources\LaporanPenjualans\Pages;

use App\Filament\Karyawan\Resources\LaporanPenjualans\LaporanPenjualanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLaporanPenjualan extends ViewRecord
{
    protected static string $resource = LaporanPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
