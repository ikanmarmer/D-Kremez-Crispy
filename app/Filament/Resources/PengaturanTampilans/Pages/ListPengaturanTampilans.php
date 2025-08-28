<?php

namespace App\Filament\Resources\PengaturanTampilans\Pages;

use App\Filament\Resources\PengaturanTampilans\PengaturanTampilanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPengaturanTampilans extends ListRecords
{
    protected static string $resource = PengaturanTampilanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
