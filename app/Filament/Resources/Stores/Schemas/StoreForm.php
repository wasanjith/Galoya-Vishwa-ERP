<?php

namespace App\Filament\Resources\Stores\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Shop Name')
                    ->placeholder('e.g., City Center Super')
                    ->required()
                    ->maxLength(255),

                TextInput::make('owner_name')
                    ->label('Owner Name')
                    ->placeholder('e.g., John Perera')
                    ->maxLength(255),

                TextInput::make('phone')
                    ->label('Mobile Number')
                    ->tel()
                    ->maxLength(50)
                    ->placeholder('e.g., +94 77 123 4567'),

                Select::make('route_id')
                    ->label('Route')
                    ->relationship('route', 'route')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->placeholder('Select a route'),

                Textarea::make('address')
                    ->label('Address')
                    ->rows(3)
                    ->maxLength(1000)
                    ->placeholder('Short address visible in listings'),

                Toggle::make('bill_to_bill_shop')
                    ->label('Bill-to-Bill Shop')
                    ->default(false)
                    ->reactive()
                    ->afterStateUpdated(function (Set $set, bool $state): void {
                        if (! $state) {
                            $set('current_liabilities', 0);
                        }
                    })
                    ->helperText('Enable to track current liabilities for this shop.'),

                TextInput::make('current_liabilities')
                    ->label('Current Liabilities')
                    ->numeric()
                    ->inputMode('decimal')
                    ->step(0.01)
                    ->prefix('Rs')
                    ->required(fn (Get $get): bool => (bool) $get('bill_to_bill_shop'))
                    ->default(0)
                    ->minValue(0)
                    ->hidden(fn (Get $get): bool => ! (bool) $get('bill_to_bill_shop'))
                    ->placeholder('Enter outstanding balance'),
            ]);
    }
}

