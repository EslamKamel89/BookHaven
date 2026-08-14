<?php

namespace App\Filament\App\Resources\BookUsers\Tables;

use App\Enums\Status;
use App\Filament\App\Resources\Books\BookResource;
use App\Filament\App\Resources\BookUsers\Pages\ListBookUsers;
use App\Models\BookUser;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BookUsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    Stack::make([
                        TextColumn::make('book.title')
                            ->size(TextSize::Large)
                            ->weight(FontWeight::SemiBold)
                            ->searchable(),
                        TextColumn::make('book.author')
                            ->searchable()
                            ->color('primary'),
                        TextColumn::make('status')
                            ->badge()
                            ->searchable(),
                        TextColumn::make('updated_at')
                            ->since()
                            ->extraAttributes(['class' => 'text-xs text-gray-500']),
                        TextColumn::make('is_return_requested')
                            ->visible(fn (?BookUser $record) => $record?->status == Status::Borrowed)
                            ->state(fn (BookUser $record) => $record->return_requested_at ? ' Return request is being processed' : '')
                            ->badge()
                            ->size('sm')
                            ->color('info'),
                    ])->space(1),
                    ImageColumn::make('book.image')
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
            ->filters([
                //
            ])
            ->recordActions([
                DeleteAction::make('cancel')
                    ->label('Cancel Request')
                    ->button()
                    ->outlined()
                    ->size('xs')
                    ->color('danger')
                    ->modalHeading('Cancel Book Request')
                    ->modalDescription('Are you sure you want to cancel your request for this book?')
                    ->modalSubmitActionLabel('Yes')
                    ->modalCancelActionLabel('No')
                    ->visible(fn (BookUser $record) => $record->status === Status::Requested),
                Action::make('return')
                    ->label('Return')
                    ->icon(Heroicon::OutlinedBookOpen)
                    ->iconPosition(IconPosition::After)
                    ->button()
                    ->outlined()
                    ->size('xs')
                    ->visible(fn (BookUser $record) => $record->status === Status::Borrowed && ! $record->return_requested_at)
                    ->modalHeading('Did you like the book')
                    ->modalDescription('Feedback help use improve recommendations')
                    ->schema([
                        Select::make('rating')
                            ->label('Rate the book')
                            ->options([
                                5 => '5 - Excellent',
                                4 => '4 - Very Good',
                                3 => '3 - Good',
                                2 => '2 - Fair',
                                1 => '1 - Poor',
                            ])->required(),
                        RichEditor::make('review')
                            ->label('Write a review'),
                    ])->action(function (BookUser $record, array $data) {
                        $record->update([
                            'return_requested_at' => now(),
                            'review' => $data['review'],
                            'rating' => $data['rating'],
                        ]);
                    }),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ])->emptyStateHeading(function (ListBookUsers $livewire) {
                $tab = $livewire->activeTab ?? null;
                foreach (Status::cases() as $case) {
                    if ($case->name === $tab) {
                        return "You have't ".$case->name.' any books yet';
                    }
                }

                return 'No books found';
            })->emptyStateIcon(Heroicon::BookOpen)
            ->emptyStateActions([
                Action::make('browse_books')
                    ->label('Browse Books')
                    ->url(BookResource::getUrl())
                    ->button(),
            ])
            ->searchPlaceholder('Search by title or author')
            ->paginated([12]);
    }
}
