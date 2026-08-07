<?php

namespace App\Filament\App\Resources\BookUsers\Pages;

use App\Enums\Status;
use App\Filament\App\Resources\BookUsers\BookUserResource;
use App\Models\BookUser;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class ListBookUsers extends ListRecords
{
    protected static string $resource = BookUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => Tab::make()
                ->icon(Heroicon::BarsArrowUp)
                ->badge(BookUser::where('user_id', auth()->id())->count()),
            Status::Requested->getLabel() => Tab::make()
                ->icon(Status::Requested->getIcon())
                ->badge(BookUser::where('user_id', auth()->id())->where('status', Status::Requested)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Status::Requested)),
            Status::Borrowed->getLabel() => Tab::make()
                ->badge(BookUser::where('user_id', auth()->id())->where('status', Status::Borrowed)->count())
                ->icon(Status::Borrowed->getIcon())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Status::Borrowed)),
            Status::Returned->getLabel() => Tab::make()
                ->badge(BookUser::where('user_id', auth()->id())->where('status', Status::Returned)->count())
                ->icon(Status::Returned->getIcon())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Status::Returned)),
        ];
    }
}
