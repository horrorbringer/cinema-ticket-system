<?php

namespace App\Filament\Resources\SeatTypes;

use App\Filament\Resources\SeatTypes\Pages\CreateSeatType;
use App\Filament\Resources\SeatTypes\Pages\EditSeatType;
use App\Filament\Resources\SeatTypes\Pages\ListSeatTypes;
use App\Filament\Resources\SeatTypes\Schemas\SeatTypeForm;
use App\Filament\Resources\SeatTypes\Tables\SeatTypesTable;
use App\Models\SeatType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SeatTypeResource extends Resource
{
    protected static ?string $model = SeatType::class;

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?int $navigationSort = 4;

    protected static UnitEnum|string|null $navigationGroup = 'Configuration';

    public static function form(Schema $schema): Schema
    {
        return SeatTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SeatTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSeatTypes::route('/'),
            'create' => CreateSeatType::route('/create'),
            'edit' => EditSeatType::route('/{record}/edit'),
        ];
    }
}
