<?php

namespace App\Filament\App\Resources\Books\Schemas;

use App\Enums\Status;
use App\Filament\App\Resources\BookUsers\BookUserResource;
use App\Filament\Infolists\Components\Rating;
use App\Models\Book;
use App\Models\BookUser;
use Filament\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;

class BookInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns([
                        'sm' => 4,
                        'xl' => 5,
                    ])
                    ->schema([
                        Grid::make()
                            ->columnSpan([
                                'sm' => 3,
                                'xl' => 4,
                            ])
                            ->dense()
                            ->schema([
                                TextEntry::make('title')
                                    ->hiddenLabel()
                                    ->extraAttributes([
                                        'class' => 'text-3xl font-bold',
                                    ])
                                    ->columnSpanFull(),
                                TextEntry::make('author')
                                    ->hiddenLabel()
                                    ->badge()
                                    ->size('xl')
                                    ->columnSpanFull(),
                                TextEntry::make('description')
                                    ->hiddenLabel()
                                    ->placeholder('-')
                                    ->extraAttributes([
                                        'class' => 'text-base p-2',
                                    ])
                                    ->columnSpanFull(),
                                Rating::make('average_rating')
                                    ->label('Rating')
                                    ->placeholder('No Ratings')
                                    ->state(fn (Book $record) => round($record->loans()->average('rating'))),
                                TextEntry::make('borrowed_count')
                                    ->label('Borrowed')
                                    ->placeholder('0')
                                    ->state(function (Book $record) {
                                        $count = $record->users()->where('status', Status::Borrowed->value)->count();

                                        return $count.' '.str('time')->plural($count);
                                    }),
                                Actions::make([
                                    Action::make('request')
                                        ->label('Request')
                                        ->button()
                                        ->outlined()
                                        ->icon(Heroicon::OutlinedBookOpen)
                                        ->iconPosition(IconPosition::After)
                                        ->extraAttributes(['class' => 'mt-3'])
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
                                            $record->refresh();
                                            if (! $record->lastLoan) {
                                                return true;
                                            }

                                            return ! in_array($record->lastLoan?->status, [Status::Borrowed, Status::Requested]);
                                        })->successNotification(
                                            Notification::make('book_request')->title('Book Requested')
                                                ->actions([
                                                    Action::make('view_requests')
                                                        ->label('View all requests')
                                                        ->button()
                                                        ->outlined()
                                                        ->size('xs')
                                                        ->url(BookUserResource::getIndexUrl()),
                                                ])
                                        )
                                        ->failureNotificationTitle("Sorry your request can't be processed, try again later. "),

                                ]),
                            ]),
                        ImageEntry::make('image')
                            ->disk('public')
                            ->hiddenLabel()
                            ->imageWidth('100%')
                            ->imageHeight('auto')
                            ->extraAttributes([
                                'class' => 'rounded-lg shadow-lg overflow-hidden',
                            ])
                            ->placeholder('-'),

                        RepeatableEntry::make('reviews')
                            ->state(fn (Book $record) => $record->loans()->whereNotNull('review')->get())
                            ->schema([
                                TextEntry::make('review')
                                    ->hiddenLabel()
                                    ->html()
                                    ->columnSpanFull(),
                                TextEntry::make('user.name')
                                    ->hiddenLabel()
                                    ->badge()
                                    ->columnSpanFull(),
                            ])
                            ->grid([
                                'default' => 1,
                                'md' => 2,
                            ])
                            ->columnSpan([
                                'xs' => 4,
                                'xl' => 5,
                            ]),

                    ]),
            ]);
    }
}
