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
use Filament\Enums\ThemeMode;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Pages\Auth\Login;


class KaryawanPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('karyawan')
            ->path('karyawan')
            ->login(Login::class)
            ->defaultThemeMode(ThemeMode::Dark)
            ->colors([
                'primary' => Color::hex(color: '#B84726'),
                'secondary' => Color::hex('#D9A16E'),
                'gray' => Color::Gray,
                'danger' => Color::hex('#E15C4A'),
                'success' => Color::hex('#6BA87D'),
                'warning' => Color::hex('#D18B4A'),
                'info' => Color::hex('#5B8FD1'),
            ])
            ->font('Poppins')
            ->maxContentWidth(Width::Full)
            ->discoverResources(in: app_path('Filament/Karyawan/Resources'), for: 'App\Filament\Karyawan\Resources')
            ->discoverPages(in: app_path('Filament/Karyawan/Pages'), for: 'App\Filament\Karyawan\Pages')
            ->pages([
                // Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Karyawan/Widgets'), for: 'App\Filament\Karyawan\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
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
