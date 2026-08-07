<?php

namespace App\Filament\App\Resources\BookUsers\Tables;

use App\Enums\Status;
use App\Filament\App\Resources\BookUsers\Pages\ListBookUsers;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
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
                    ImageColumn::make('book.image')
                        ->disk('public')
                        ->imageWidth('100px')
                        ->imageHeight('auto')
                        ->grow(false),
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
                    ])->space(1),
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
                // EditAction::make(),
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
            ->searchPlaceholder('Search by title or author')
            ->paginated([12]);
    }
}
