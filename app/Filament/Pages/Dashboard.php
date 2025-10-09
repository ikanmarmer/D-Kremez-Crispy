<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class Dashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Home;

    // override header sehingga tidak ada heading
    public function getHeading(): string
    {
        return '';
    }
}
