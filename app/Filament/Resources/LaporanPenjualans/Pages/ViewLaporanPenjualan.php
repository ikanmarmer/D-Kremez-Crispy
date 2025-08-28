<?php

namespace App\Filament\Resources\LaporanPenjualans\Pages;

use App\Filament\Resources\LaporanPenjualans\LaporanPenjualanResource;
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
