<?php

namespace App\Enums;

use App\Traits\HasEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum Status: string implements HasColor, HasIcon, HasLabel
{
    use HasEnum;
    case Requested = 'requested';
    case Borrowed = 'borrowed';
    case Returned = 'returned';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Requested => 'Requested',
            self::Borrowed => 'Borrowed',
            self::Returned => 'Returned',
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {
            self::Requested => 'warning',
            self::Borrowed => 'success',
            self::Returned => 'gray',
        };
    }

    public function getIcon(): Htmlable|string|\BackedEnum|null
    {
        return match ($this) {
            self::Requested => 'heroicon-o-clock',
            self::Borrowed => 'heroicon-o-book-open',
            self::Returned => 'heroicon-o-arrow-uturn-left',
        };
    }
}
