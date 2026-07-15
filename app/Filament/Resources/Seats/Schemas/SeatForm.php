<?php

namespace App\Filament\Resources\Seats\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SeatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('hall_id')
                    ->required()
                    ->relationship('hall', 'name'),
                Select::make('seat_type_id')
                    ->required()
                    ->relationship('seatType', 'name'),
                TextInput::make('row')
                    ->required(),
                TextInput::make('number')
                    ->required()
                    ->numeric(),
                TextInput::make('label')
                    ->required(),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
