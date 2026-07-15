<?php

namespace App\Filament\Resources\Showtimes\Schemas;

use App\Rules\NoShowtimeOverlap;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ShowtimeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('movie_id')
                    ->required()
                    ->relationship('movie', 'title')
                    ->searchable()
                    ->preload(),
                Select::make('hall_id')
                    ->required()
                    ->relationship('hall', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                        $set('start_time', null);
                        $set('end_time', null);
                    }),
                DateTimePicker::make('start_time')
                    ->required()
                    ->minutesStep(5)
                    ->rules(function (\Filament\Forms\Get $get) {
                        $hallId = $get('hall_id');
                        $recordId = request()->route('record');
                        return $hallId ? [new NoShowtimeOverlap($hallId, $recordId)] : [];
                    }),
                DateTimePicker::make('end_time')
                    ->required()
                    ->after('start_time')
                    ->minutesStep(5),
                TextInput::make('base_price')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->minValue(0),
            ]);
    }
}
