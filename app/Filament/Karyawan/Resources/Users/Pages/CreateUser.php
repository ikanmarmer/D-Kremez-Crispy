<?php

namespace App\Filament\Karyawan\Resources\Users\Pages;

use App\Filament\Karyawan\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
