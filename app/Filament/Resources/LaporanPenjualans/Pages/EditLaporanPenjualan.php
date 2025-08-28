<?php

namespace App\Filament\Resources\LaporanPenjualans\Pages;

use App\Filament\Resources\LaporanPenjualans\LaporanPenjualanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLaporanPenjualan extends EditRecord
{
    protected static string $resource = LaporanPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
