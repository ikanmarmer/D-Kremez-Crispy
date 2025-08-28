<?php

namespace App\Filament\Resources\DetailLaporanPenjualans\Pages;

use App\Filament\Resources\DetailLaporanPenjualans\DetailLaporanPenjualanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDetailLaporanPenjualan extends ViewRecord
{
    protected static string $resource = DetailLaporanPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
