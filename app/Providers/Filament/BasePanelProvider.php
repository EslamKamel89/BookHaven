<?php

namespace App\Providers\Filament;

use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

abstract class BasePanelProvider extends PanelProvider
{
    public function basePanel(Panel $panel): Panel
    {
        return $panel
            ->brandName('Book Nest')
            ->brandLogo('/images/booknest-logo.png')
            ->brandLogoHeight('2.4rem')
            ->favicon('/images/favicon.ico')
            ->colors([
                'primary' => Color::Lime,
                'gray' => Color::Slate,
            ])
            // ->darkMode(isForced: true)
            ->defaultThemeMode(ThemeMode::Dark)
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->sidebarWidth('w-fit')
            // ->sidebarCollapsibleOnDesktop()
            ->topNavigation(true)
            ->spa()
            ->font('Source Sans 3')
            ->viteTheme('resources/css/filament/theme.css');
    }
}
