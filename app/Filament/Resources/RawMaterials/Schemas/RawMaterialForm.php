<?php

namespace App\Filament\Resources\RawMaterials\Schemas;

use App\Models\RawMaterialCategory;
use App\Models\Supplier;
use App\Models\Unit;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RawMaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Raw Material Name')
                    ->placeholder('e.g., Steel Rods, Plastic Pellets, Chemical X')
                    ->required()
                    ->maxLength(255)
                    ->helperText('The name of the raw material'),

                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->options(RawMaterialCategory::where('is_active', true)->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->helperText('Select the category this raw material belongs to'),

                Select::make('unit_id')
                    ->label('Unit of Measurement')
                    ->relationship('unit', 'name')
                    ->options(Unit::where('is_active', true)->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->helperText('Select the unit of measurement for this raw material'),

                TextInput::make('cost_per_unit')
                    ->label('Cost Per Unit')
                    ->numeric()
                    ->inputMode('decimal')
                    ->step(0.01)
                    ->minValue(0)
                    ->required()
                    ->prefix('$')
                    ->helperText('The cost per unit of measurement'),

                TextInput::make('minimum_stock_level')
                    ->label('Minimum Stock Level')
                    ->numeric()
                    ->inputMode('numeric')
                    ->minValue(0)
                    ->required()
                    ->helperText('The minimum stock level before reordering is needed'),

                Select::make('supplier_id')
                    ->label('Supplier')
                    ->relationship('supplier', 'name')
                    ->options(Supplier::where('is_active', true)->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->helperText('Select the primary supplier for this raw material'),

                Checkbox::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->helperText('Whether this raw material is currently active and available for use'),
            ]);
    }
}
