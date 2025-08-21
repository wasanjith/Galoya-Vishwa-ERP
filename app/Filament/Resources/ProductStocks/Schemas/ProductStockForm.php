<?php

namespace App\Filament\Resources\ProductStocks\Schemas;

use App\Models\Product;
use App\Models\ProductCategory;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductStockForm
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

                TextInput::make('batch_number')
                    ->label('Batch Number')
                    ->placeholder('e.g., BATCH-2024-001')
                    ->required()
                    ->maxLength(100)
                    ->helperText('Unique batch or lot number for tracking'),

                TextInput::make('quantity_available')
                    ->label('Quantity Available')
                    ->placeholder('0')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->step(1)
                    ->helperText('Current available stock quantity'),

                DatePicker::make('manufactured_date')
                    ->label('Manufactured Date')
                    ->required()
                    ->maxDate(now())
                    ->helperText('Date when the product was manufactured'),

                DatePicker::make('expiry_date')
                    ->label('Expiry Date')
                    ->required()
                    ->minDate(now())
                    ->helperText('Date when the product expires'),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'fresh' => 'Fresh',
                        'near_expiry' => 'Near Expiry',
                        'expired' => 'Expired',
                    ])
                    ->default('fresh')
                    ->required()
                    ->helperText('Current status of the stock'),
            ]);
    }
}
