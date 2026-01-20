<?php

namespace App\Filament\Resources\VehicleMaintenances;

use App\Filament\Resources\VehicleMaintenances\Pages\CreateVehicleMaintenance;
use App\Filament\Resources\VehicleMaintenances\Pages\EditVehicleMaintenance;
use App\Filament\Resources\VehicleMaintenances\Pages\ListVehicleMaintenances;
use App\Filament\Resources\VehicleMaintenances\Schemas\VehicleMaintenanceForm;
use App\Filament\Resources\VehicleMaintenances\Tables\VehicleMaintenancesTable;
use App\Models\VehicleMaintenance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class VehicleMaintenanceResource extends Resource
{
    protected static ?string $model = VehicleMaintenance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrench;

    protected static UnitEnum|string|null $navigationGroup = 'Fleet & Logistics';

    protected static ?string $navigationLabel = 'Maintenance Jobs';

    protected static ?int $navigationSort = 11;

    public static function form(Schema $schema): Schema
    {
        return VehicleMaintenanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehicleMaintenancesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVehicleMaintenances::route('/'),
            'create' => CreateVehicleMaintenance::route('/create'),
            'edit' => EditVehicleMaintenance::route('/{record}/edit'),
        ];
    }
}


