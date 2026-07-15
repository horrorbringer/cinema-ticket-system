<?php

namespace App\Filament\Resources\SeatTypes\Pages;

use App\Filament\Resources\SeatTypes\SeatTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSeatType extends EditRecord
{
    protected static string $resource = SeatTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
