<?php

namespace App\Filament\App\Widgets;

use App\Enums\Status;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReadingStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {

        $booksRead = auth()->user()->loans()->where('status', Status::Returned->value)->count();
        $daysSinceJoining = auth()->user()->created_at->diffInDays(now());

        return array_map(function (Stat $stat) {
            return $stat->color('primary')->extraAttributes([
                'class' => 'w-full',
            ]);
        }, [
            Stat::make('Reading Since', (int) $daysSinceJoining)
                ->description('Days since you joined')
                ->descriptionIcon(Heroicon::Calendar),
            Stat::make('Books Read', $booksRead)
                ->description('Total Books Read till date')
                ->descriptionIcon(Heroicon::NumberedList),
            Stat::make('Reading Rate', function () use ($booksRead, $daysSinceJoining) {
                $monthsSinceJoining = $daysSinceJoining / 30;
                if ($monthsSinceJoining === 0.0) {
                    return 0;
                }

                return round($booksRead / $monthsSinceJoining);
            })->description('Average Books per month')
                ->descriptionIcon(Heroicon::Calculator),
        ]);
    }
}
