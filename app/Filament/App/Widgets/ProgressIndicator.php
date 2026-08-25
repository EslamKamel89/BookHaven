<?php

namespace App\Filament\App\Widgets;

use App\Enums\Status;
use Filament\Widgets\Widget;

class ProgressIndicator extends Widget
{
    protected string $view = 'filament.app.widgets.progress-indicator';

    public int $booksRead = 0;

    public int $target = 10;

    public int $progress = 0;

    public function mount()
    {
        $user = auth()->user();
        $this->booksRead = $user->loans()->where('status', Status::Returned->value)->count();
        $this->progress = min(100, ($this->booksRead / $this->target) * 100);
    }
}
