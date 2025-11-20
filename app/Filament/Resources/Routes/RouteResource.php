<?php

namespace App\Filament\Resources\Routes;

use App\Filament\Resources\Routes\Pages\CreateRoute;
use App\Filament\Resources\Routes\Pages\EditRoute;
use App\Filament\Resources\Routes\Pages\ListRoutes;
use App\Filament\Resources\Routes\Schemas\RouteForm;
use App\Filament\Resources\Routes\Tables\RoutesTable;
use App\Models\Route as RouteModel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RouteResource extends Resource
{
    protected static ?string $model = RouteModel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static UnitEnum|string|null $navigationGroup = 'Sales & Distribution';

    protected static ?string $navigationLabel = 'Routes';

    protected static ?string $modelLabel = 'Route';

    protected static ?string $pluralModelLabel = 'Routes';

    protected static ?int $navigationSort = 0;

    public static function form(Schema $schema): Schema
    {
        return RouteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoutesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoutes::route('/'),
            'create' => CreateRoute::route('/create'),
            'edit' => EditRoute::route('/{record}/edit'),
        ];
    }
}

