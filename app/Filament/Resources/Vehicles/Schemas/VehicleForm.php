<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Vehicle Profile')
                    ->schema([
                        TextInput::make('name')
                            ->label('Vehicle Name')
                            ->placeholder('e.g., Distribution Lorry 01')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('number_plate')
                            ->label('Number Plate')
                            ->placeholder('XX-1234')
                            ->required()
                            ->maxLength(25)
                            ->unique(ignoreRecord: true),

                        TextInput::make('net_worth')
                            ->label('Net Worth (LKR)')
                            ->numeric()
                            ->prefix('Rs')
                            ->minValue(0)
                            ->step(0.01),
                    ])
                    ->columns(3),

                Section::make('Performance & Fuel')
                    ->schema([
                        TextInput::make('current_mileage')
                            ->label('Current Mileage (km)')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('km')
                            ->default(0),

                        TextInput::make('diesel_efficiency')
                            ->label('Diesel Efficiency')
                            ->helperText('Kilometers per liter')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.1)
                            ->suffix('km/L'),
                    ])
                    ->columns(2),

                Section::make('Maintenance Tracking')
                    ->schema([
                        Select::make('tyre_condition')
                            ->label('Tyre Condition')
                            ->options([
                                'excellent' => 'Excellent',
                                'good' => 'Good',
                                'average' => 'Average',
                                'needs_attention' => 'Needs Attention',
                            ])
                            ->placeholder('Select condition')
                            ->native(false),

                        DatePicker::make('last_service_date')
                            ->label('Last Service Date')
                            ->native(false),

                        DatePicker::make('next_service_date')
                            ->label('Next Service Date')
                            ->native(false),

                        DatePicker::make('last_tyre_replace_date')
                            ->label('Last Tyre Replace Date')
                            ->native(false),
                    ])
                    ->columns(2),
            ]);
    }
}


