<?php

namespace App\Filament\Resources\DetailLaporanPenjualans\Pages;

use App\Filament\Resources\DetailLaporanPenjualans\DetailLaporanPenjualanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDetailLaporanPenjualan extends EditRecord
{
    protected static string $resource = DetailLaporanPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
