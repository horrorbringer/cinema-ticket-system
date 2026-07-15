<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->required()
                    ->relationship('user', 'name')
                    ->searchable(),
                Select::make('showtime_id')
                    ->required()
                    ->relationship('showtime', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->movie->title} - {$record->start_time->format('M j, h:i A')}")
                    ->searchable(),
                TextInput::make('booking_number')
                    ->required(),
                TextInput::make('total_seats')
                    ->required()
                    ->numeric(),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                        'refunded' => 'Refunded',
                    ])
                    ->default('pending')
                    ->required(),
                DateTimePicker::make('expires_at'),
            ]);
    }
}
