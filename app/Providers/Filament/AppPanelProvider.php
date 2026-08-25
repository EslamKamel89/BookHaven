<?php

namespace App\Providers\Filament;

use App\Enums\Status;
use App\Filament\App\Resources\Books\BookResource;
use App\Filament\App\Resources\BookUsers\BookUserResource;
use App\Models\BookUser;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

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
            ->widgets([])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make('')->items([
                        NavigationItem::make('Dashboard')
                            ->icon(Heroicon::OutlinedHome)
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.app.pages.dashboard'))
                            ->url(fn (): string => Dashboard::getUrl()),
                        ...BookResource::getNavigationItems(),
                    ]),
                    NavigationGroup::make('My Shelf')->items([
                        NavigationItem::make('All')
                            ->badge(BookUser::where('user_id', auth()->id())->count())
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.app.resources.my-books.index') && request()->input('tab') === null)
                            ->icon(Heroicon::BarsArrowUp)
                            ->url(BookUserResource::getUrl()),

                        NavigationItem::make('Requested Books')
                            ->badge(BookUser::where('user_id', auth()->id())->where('status', Status::Requested)->count())
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.app.resources.my-books.index') && request()->input('tab') === Status::Requested->getLabel())
                            ->icon(Status::Requested->getIcon())
                            ->url(BookUserResource::getUrl().'?tab='.Status::Requested->getLabel()),
                        NavigationItem::make('Currently Reading')
                            ->badge(BookUser::where('user_id', auth()->id())->where('status', Status::Borrowed)->count())
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.app.resources.my-books.index') && request()->input('tab') === Status::Borrowed->getLabel())
                            ->icon(Status::Borrowed->getIcon())
                            ->url(BookUserResource::getUrl().'?tab='.Status::Borrowed->getLabel()),
                        NavigationItem::make('Past Reads')
                            ->badge(BookUser::where('user_id', auth()->id())->where('status', Status::Returned)->count())
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.app.resources.my-books.index') && request()->input('tab') === Status::Returned->getLabel())
                            ->icon(Status::Returned->getIcon())
                            ->url(BookUserResource::getUrl().'?tab='.Status::Returned->getLabel()),
                    ]),

                ]);
            });
    }
}
