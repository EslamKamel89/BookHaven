<?php

namespace App\Filament\App\Resources\Books\Tables;

use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    ImageColumn::make('image')
                        ->disk('public')
                        ->imageWidth('100px')
                        ->imageHeight('auto')
                        ->grow(false),
                    Stack::make([
                        TextColumn::make('title')
                            ->size(TextSize::Large)
                            ->weight(FontWeight::SemiBold)
                            ->searchable(),
                        TextColumn::make('author')
                            ->searchable()
                            ->color('primary'),
                    ])->space(1),
                    // View::make('filament.app.resources.books.book-info'),
                ]),

            ])->contentGrid([
                'default' => 1,
                'md' => 2,
                'xl' => 3,
            ])
            ->searchPlaceholder('Search by title or author')
            ->paginated([12]);
    }
}
