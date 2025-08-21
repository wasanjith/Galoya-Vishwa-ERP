<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use App\Models\Product;
use App\Models\ProductCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class StockMovementForm
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
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('product_id', null);
                    }),

                Select::make('product_id')
                    ->label('Product')
                    ->options(function (Get $get) {
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
                    ->disabled(fn (Get $get) => !$get('category_filter'))
                    ->reactive(),

                Select::make('movement_type')
                    ->label('Movement Type')
                    ->options([
                        'purchase' => 'Purchase (Stock In)',
                        'sale' => 'Sale (Stock Out)',
                        'transfer' => 'Transfer (Stock Movement)',
                        'adjustment' => 'Adjustment (Stock Correction)',
                        'return' => 'Return (Stock In)',
                    ])
                    ->required()
                    ->default('purchase')
                    ->helperText('Select the type of stock movement'),

                TextInput::make('batch_number')
                    ->label('Batch Number')
                    ->placeholder('e.g., BATCH-2024-001')
                    ->maxLength(100)
                    ->helperText('Optional: Batch or lot number for tracking'),

                TextInput::make('quantity')
                    ->label('Quantity')
                    ->placeholder('0.00')
                    ->required()
                    ->numeric()
                    ->step(0.01)
                    ->helperText('Quantity for this movement (positive for stock in, negative for stock out)'),

                Textarea::make('notes')
                    ->label('Notes')
                    ->placeholder('Additional details about this movement...')
                    ->rows(3)
                    ->maxLength(500)
                    ->helperText('Optional: Additional notes or comments about this movement'),
            ]);
    }
}
