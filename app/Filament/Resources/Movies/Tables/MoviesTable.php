<?php

namespace App\Filament\Resources\Movies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MoviesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('poster')
                    ->square()
                    ->width(50),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('language')
                    ->sortable(),
                TextColumn::make('duration')
                    ->suffix(' min')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'now_showing' => 'success',
                        'coming_soon' => 'warning',
                        'ended' => 'danger',
                    }),
                TextColumn::make('rating')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
