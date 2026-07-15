<?php

namespace App\Filament\Resources\Movies\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MovieForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description'),
                FileUpload::make('poster')
                    ->image()
                    ->directory('movies/posters'),
                TextInput::make('trailer'),
                TextInput::make('duration')
                    ->required()
                    ->numeric()
                    ->suffix('min'),
                TextInput::make('language')
                    ->required(),
                Select::make('genres')
                    ->multiple()
                    ->relationship('genres', 'name')
                    ->preload(),
                DatePicker::make('release_date')
                    ->required(),
                TextInput::make('rating')
                    ->numeric()
                    ->step(0.1)
                    ->minValue(0)
                    ->maxValue(10),
                Select::make('status')
                    ->required()
                    ->options([
                        'coming_soon' => 'Coming Soon',
                        'now_showing' => 'Now Showing',
                        'ended' => 'Ended',
                    ]),
            ]);
    }
}
