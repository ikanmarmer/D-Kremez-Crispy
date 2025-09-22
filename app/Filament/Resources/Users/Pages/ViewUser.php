<?php

namespace App\Filament\Resources\Users\Pages;

use App\Enums\Role;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Detail Pengguna';

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->hidden(fn($record) => $record->role === Role::User),
        ];
    }
}
