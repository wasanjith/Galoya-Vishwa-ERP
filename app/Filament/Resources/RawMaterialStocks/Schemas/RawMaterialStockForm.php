<?php

namespace App\Filament\Resources\RawMaterialStocks\Schemas;

use App\Models\RawMaterial;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RawMaterialStockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('raw_material_id')
                    ->label('Raw Material')
                    ->options(RawMaterial::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->placeholder('Select a raw material'),

                TextInput::make('quantity_available')
                    ->label('Quantity Available')
                    ->numeric()
                    ->step(0.001)
                    ->minValue(0)
                    ->required()
                    ->placeholder('0.000'),

                DateTimePicker::make('last_updated')
                    ->label('Last Updated')
                    ->default(now())
                    ->required(),
            ]);
    }
}
