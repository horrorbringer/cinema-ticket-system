<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('booking_id')
                    ->required()
                    ->numeric(),
                TextInput::make('seat_id')
                    ->required()
                    ->numeric(),
                TextInput::make('qr_code'),
                TextInput::make('ticket_number')
                    ->required(),
                Select::make('status')
                    ->options(['active' => 'Active', 'used' => 'Used', 'refunded' => 'Refunded'])
                    ->default('active')
                    ->required(),
            ]);
    }
}
