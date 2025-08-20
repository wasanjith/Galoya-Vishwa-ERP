<?php

namespace App\Filament\Resources\Units\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Unit Name')
                    ->placeholder('e.g., Kilograms, Liters, Pieces')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('The full name of the unit (e.g., Kilograms)'),
                
                TextInput::make('symbol')
                    ->label('Unit Symbol')
                    ->placeholder('e.g., kg, L, pcs')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('The abbreviated symbol for the unit (e.g., kg)'),
                
                Checkbox::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->helperText('Whether this unit is currently active and available for use'),
            ]);
    }
}
