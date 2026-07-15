<?php

namespace App\Filament\Resources\Genres\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GenreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->live(onBlur: true)
                    ->afterStateUpdate(fn (string $operation, $state, \Filament\Forms\Set $set) => $operation === 'create' ? $set('slug', str($state)->slug()) : null),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
            ]);
    }
}
