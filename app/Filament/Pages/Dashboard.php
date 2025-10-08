<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    // override header sehingga tidak ada heading
    public function getHeading(): string
    {
        return '';
    }
}
