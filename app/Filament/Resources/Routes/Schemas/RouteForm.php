<?php

namespace App\Filament\Resources\Routes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RouteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('route')
                    ->label('Route Name')
                    ->placeholder('e.g., Colombo North')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}

