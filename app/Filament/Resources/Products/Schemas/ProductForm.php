<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\ProductCategory;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category')
                    ->label('Category')
                    ->options(ProductCategory::where('is_active', true)->pluck('name', 'name'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Select the product category'),
                    
                TextInput::make('name')
                    ->label('Product Name')
                    ->placeholder('e.g., Strawberry Yogurt 500ml')
                    ->required()
                    ->maxLength(255)
                    ->helperText('The full name of the product'),

                TextInput::make('product_code')
                    ->label('Product Code')
                    ->placeholder('e.g., YOG-001')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true)
                    ->helperText('Unique identifier code for the product'),

                Repeater::make('selling_prices')
                    ->label('Selling Prices')
                    ->schema([
                        Select::make('type')
                            ->label('Price Type')
                            ->options([
                                'retail' => 'Retail',
                                'wholesale' => 'Wholesale',
                                'bulk' => 'Bulk',
                                'guest' => 'Guest',
                            ])
                            ->required()
                            ->placeholder('Select price type')
                            ->columnSpan(1),
                        
                        TextInput::make('price')
                            ->label('Price')
                            ->placeholder('0.00')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->prefix('₹')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->addActionLabel('Add Price')
                    ->reorderable(false)
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['type'] ?? 'Price')
                    ->helperText('Add different pricing tiers (retail, wholesale, bulk, guest)'),

                TextInput::make('cost_price')
                    ->label('Cost Price')
                    ->placeholder('0.00')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->helperText('The cost price of the product'),

                Checkbox::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->helperText('Whether this product is currently active and available for sale'),
            ]);
    }
}
