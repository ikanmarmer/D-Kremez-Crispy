<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class LoginPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('login')
            ->login(\App\Filament\Pages\Auth\Login::class)
            ->colors([
                'primary' => Color::hex('#8B4513'), // Coklat tua (seperti coklat chocolate)
                'secondary' => Color::hex('#D2B48C'), // Coklat tan (warna krem kecoklatan)
                'gray' => Color::Gray,
                'danger' => Color::Red,
                'success' => Color::Green,
                'warning' => Color::Amber,
                'info' => Color::Blue,
            ])
            ->font('Poppins')
            ->maxContentWidth(Width::Full)
            // ->discoverResources(in: app_path('Filament/Login/Resources'), for: 'App\Filament\Login\Resources')
            // ->discoverPages(in: app_path('Filament/Login/Pages'), for: 'App\Filament\Login\Pages')
            // ->pages([
            //     Dashboard::class,
            // ])
            // ->discoverWidgets(in: app_path('Filament/Login/Widgets'), for: 'App\Filament\Login\Widgets')
            // ->widgets([
            //     AccountWidget::class,
            //     FilamentInfoWidget::class,
            // ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])->viteTheme('resources/css/filament/admin/theme.css');
    }
}
