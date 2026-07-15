<?php

namespace App\Filament\Resources\SeatTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SeatTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('color')
                    ->required(),
                TextInput::make('price_multiplier')
                    ->required()
                    ->numeric()
                    ->default(1.0),
            ]);
    }
}
