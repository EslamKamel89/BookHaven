<?php

namespace App\Filament\App\Resources\Books\Pages;

use App\Filament\App\Resources\Books\BookResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewBook extends ViewRecord
{
    protected static string $resource = BookResource::class;

    public function getHeading(): string|Htmlable|null
    {
        return '';
    }

    public function getTitle(): string|Htmlable
    {
        return $this->record->title;
    }

    protected function getHeaderActions(): array
    {

        return [
            EditAction::make(),
        ];
    }
}
