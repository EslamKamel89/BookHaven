<?php

namespace App\Providers\Filament;

use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\Support\Colors\Color;

class AppPanelProvider extends BasePanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel = $this->basePanel($panel);

        return $panel
            ->id('app')
            ->path('/app')
            ->default()
            ->login()
            ->registration()
            ->passwordReset()
            ->profile()
            ->colors([
                'primary' => Color::Lime,
                'gray' => Color::Slate,
            ])
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\Filament\App\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\Filament\App\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\Filament\App\Widgets')
            ->widgets([]);
    }
}
