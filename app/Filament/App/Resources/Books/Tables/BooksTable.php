<?php

namespace App\Filament\App\Resources\Books\Tables;

use App\Enums\Status;
use App\Filament\Tables\Columns\RatingColumn;
use App\Models\Book;
use App\Models\BookUser;
use Filament\Actions\Action;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    Stack::make([
                        Stack::make([
                            TextColumn::make('title')
                                ->size(TextSize::Large)
                                ->weight(FontWeight::SemiBold)
                                ->searchable(),
                            TextColumn::make('author')
                                ->searchable()
                                ->color('primary'),
                            // for some reason the first attempt to add status don't work but the second one work.
                            // TextColumn::make('lastLoan.status'),
                            TextColumn::make('status')
                                ->state(fn ($record) => $record?->lastLoan?->status),
                        ]),
                        RatingColumn::make('loans_avg_rating'),
                    ])->space(3),
                    ImageColumn::make('image')
                        ->disk('public')
                        ->imageWidth('100px')
                        ->imageHeight('auto')
                        ->grow(false),
                ]),

            ])->contentGrid([
                'default' => 1,
                'md' => 2,
                'xl' => 3,
            ])
            ->recordActions([
                Action::make('request')
                    ->label('Request')
                    ->button()
                    ->outlined()
                    ->icon(Heroicon::OutlinedBookOpen)
                    ->iconPosition(IconPosition::After)
                    ->size('xs')
                    ->action(function (Book $record) {
                        BookUser::updateOrCreate([
                            'user_id' => auth()->id(),
                            'book_id' => $record->id,
                        ], [
                            'status' => Status::Requested,
                            'requested_at' => now(),
                        ]);
                    })->visible(function (Book $record) {
                        // dump($record)->lastLoan
                        if (! $record->lastLoan) {
                            return true;
                        }

                        return ! in_array($record->lastLoan?->status, [Status::Borrowed, Status::Requested]);
                    }),
            ])
            ->searchPlaceholder('Search by title or author')
            ->paginated([12]);
    }
}
