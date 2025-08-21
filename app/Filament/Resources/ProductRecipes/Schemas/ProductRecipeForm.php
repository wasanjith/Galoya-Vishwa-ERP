<?php

namespace App\Filament\Resources\ProductRecipes\Schemas;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\RawMaterial;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductRecipeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_filter')
                    ->label('Product Category')
                    ->options(ProductCategory::where('is_active', true)->pluck('name', 'name'))
                    ->searchable()
                    ->preload()
                    ->placeholder('Select a category first')
                    ->helperText('Choose a category to filter available products')
                    ->live(),

                Select::make('product_id')
                    ->label('Product')
                    ->options(function ($get) {
                        $selectedCategory = $get('category_filter');
                        
                        if (!$selectedCategory) {
                            return [];
                        }
                        
                        return Product::where('is_active', true)
                            ->where('category', $selectedCategory)
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Select the product from the chosen category')
                    ->disabled(fn ($get) => !$get('category_filter')),

                Repeater::make('recipe_ingredients')
                    ->label('Recipe Ingredients')
                    ->schema([
                        Select::make('raw_material_id')
                            ->label('Raw Material')
                            ->options(RawMaterial::where('is_active', true)->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Select raw material')
                            ->helperText('Choose the raw material for this ingredient'),

                        TextInput::make('quantity_required')
                            ->label('Quantity Required')
                            ->placeholder('0.00')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->helperText('Quantity needed per unit of product'),
                    ])
                    ->columns(2)
                    ->addActionLabel('Add Ingredient')
                    ->reorderable(false)
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => 
                        isset($state['raw_material_id']) 
                            ? RawMaterial::find($state['raw_material_id'])?->name ?? 'Ingredient'
                            : 'Ingredient'
                    )
                    ->helperText('Add all raw materials and quantities needed for this product recipe'),
            ]);
    }
}
