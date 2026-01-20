<?php

namespace App\Filament\Resources\VehicleMaintenances\Schemas;

use App\Enums\VehicleMaintenanceType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class VehicleMaintenanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Job Summary')
                    ->schema([
                        Select::make('vehicle_id')
                            ->label('Vehicle')
                            ->relationship('vehicle', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->getOptionLabelFromRecordUsing(fn ($record): string => $record->displayLabel()),

                        Select::make('maintenance_type')
                            ->label('Maintenance Type')
                            ->options(VehicleMaintenanceType::options())
                            ->live()
                            ->required()
                            ->native(false),

                        DatePicker::make('performed_at')
                            ->label('Performed Date')
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state, Get $get): void {
                                if (! $state) {
                                    return;
                                }

                                $type = $get('maintenance_type');

                                if ($type === VehicleMaintenanceType::Service->value) {
                                    $set('last_service_date', $state);
                                }

                                if ($type === VehicleMaintenanceType::Tyre->value) {
                                    $set('last_tyre_replace_date', $state);
                                }
                            }),

                        TextInput::make('cost')
                            ->label('Maintenance Cost')
                            ->numeric()
                            ->prefix('Rs')
                            ->minValue(0)
                            ->default(0)
                            ->step(0.01),

                        TextInput::make('mileage_at_service')
                            ->label('Mileage at Job')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('km'),
                    ])
                    ->columns(2),

                Section::make('People & Notes')
                    ->schema([
                        Select::make('driver_id')
                            ->label('Participated Driver')
                            ->relationship('driver', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Optional')
                            ->native(false),

                        Textarea::make('details')
                            ->label('Work Summary / Notes')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Update Vehicle Timelines')
                    ->description('Dates saved here will sync with the vehicle record automatically.')
                    ->schema([
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
                    ->columns(3),
            ]);
    }
}


