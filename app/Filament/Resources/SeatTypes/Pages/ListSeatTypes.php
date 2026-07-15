<?php

namespace App\Filament\Resources\SeatTypes\Pages;

use App\Filament\Resources\SeatTypes\SeatTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSeatTypes extends ListRecords
{
    protected static string $resource = SeatTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
