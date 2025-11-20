<?php

namespace App\Filament\Resources\CreditSales\Schemas;

use App\Models\CreditSale;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Store;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class CreditSaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Route & Store')
                    ->description('Choose the route first to filter stores quickly.')
                    ->schema([
                        Placeholder::make('invoice_number_display')
                            ->label('Invoice Number')
                            ->content(fn (?CreditSale $record): string => $record?->invoice_number ?? 'Will be generated automatically when saved.')
                            ->columnSpanFull(),

                        Select::make('route_id')
                            ->label('Route')
                            ->relationship('route', 'route')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (Set $set): void {
                                $set('store_id', null);
                            }),

                        Select::make('store_id')
                            ->label('Store')
                            ->options(function (Get $get): array {
                                $routeId = $get('route_id');

                                if (! $routeId) {
                                    return [];
                                }

                                return Store::query()
                                    ->where('route_id', $routeId)
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn (Get $get): bool => blank($get('route_id')))
                            ->hint('Select the route to see stores under it.')
                            ->reactive(),

                        DatePicker::make('sale_date')
                            ->label('Sale Date')
                            ->default(now())
                            ->maxDate(now())
                            ->required(),
                    ])
                    ->columns([
                        'sm' => 2,
                        'lg' => 3,
                    ]),

                Section::make('Products')
                    ->description('Add every product in this credit sale. You can add multiple lines.')
                    ->schema([
                        self::itemsRepeater(),
                    ])
                    ->collapsible(),

                Section::make('Payment Summary')
                    ->schema([
                        TextInput::make('grand_total')
                            ->label('Grand Total')
                            ->prefix('Rs')
                            ->numeric()
                            ->default(0)
                            ->dehydrated()
                            ->dehydrated(true),

                        TextInput::make('amount_paid')
                            ->label('Paid Now')
                            ->prefix('Rs')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::syncTotals($set, $get)),

                        TextInput::make('credit_total')
                            ->label('Credit Balance')
                            ->prefix('Rs')
                            ->numeric()
                            ->default(0)
                            ->dehydrated()
                            ->dehydrated(true),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->placeholder('Any quick notes about this credit sale...')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ])
                    ->columns([
                        'sm' => 2,
                        'lg' => 2,
                    ]),
            ]);
    }

    protected static function itemsRepeater(): Repeater
    {
        return Repeater::make('items')
            ->label('Credit Sale Items')
            ->relationship('items')
            ->createItemButtonLabel('Add Product')
            ->minItems(1)
            ->live()
            ->columns(12)
            ->schema([
                Select::make('product_category_id')
                    ->label('Product Category')
                    ->options(fn () => ProductCategory::query()->orderBy('name')->pluck('name', 'id')->all())
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (Set $set): void {
                        $set('product_id', null);
                    })
                    ->columnSpan([
                        'sm' => 6,
                        'lg' => 3,
                    ]),

                Select::make('product_id')
                    ->label('Product')
                    ->options(function (Get $get): array {
                        $categoryId = $get('product_category_id');

                        $query = Product::query()->orderBy('name');

                        if ($categoryId) {
                            $category = ProductCategory::find($categoryId);

                            if ($category) {
                                $query->where('category', $category->name);
                            }
                        }

                        return $query->pluck('name', 'id')->all();
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(fn (Get $get): bool => blank($get('product_category_id')))
                    ->columnSpan([
                        'sm' => 6,
                        'lg' => 3,
                    ]),

                TextInput::make('quantity')
                    ->label('Qty')
                    ->numeric()
                    ->required()
                    ->minValue(0.01)
                    ->step(0.01)
                    ->placeholder('0.00')
                    ->columnSpan([
                        'sm' => 6,
                        'lg' => 2,
                    ]),

                TextInput::make('unit_price')
                    ->label('Unit Price')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->step(0.01)
                    ->prefix('Rs')
                    ->placeholder('0.00')
                    ->columnSpan([
                        'sm' => 6,
                        'lg' => 2,
                    ]),

                Placeholder::make('line_total_preview')
                    ->label('Line Total')
                    ->content(fn (Get $get): string => self::formatCurrency(self::calculateItemTotal($get)))
                    ->extraAttributes(['class' => 'text-base font-semibold'])
                    ->columnSpan([
                        'sm' => 12,
                        'lg' => 2,
                    ]),
            ])
            ->afterStateUpdated(fn (Set $set, Get $get) => self::syncTotals($set, $get))
            ->itemLabel(fn (array $state): ?string => data_get($state, 'product_id') ? 'Product Line' : null)
            ->columnSpanFull();
    }

    protected static function syncTotals(Set $set, Get $get): void
    {
        $items = collect($get('items') ?? []);

        $grandTotal = $items->sum(function (array $item): float {
            $quantity = (float) ($item['quantity'] ?? 0);
            $unitPrice = (float) ($item['unit_price'] ?? 0);

            return round($quantity * $unitPrice, 2);
        });

        $grandTotal = round($grandTotal, 2);

        $set('grand_total', $grandTotal);

        $amountPaid = (float) ($get('amount_paid') ?? 0);

        if ($amountPaid > $grandTotal) {
            $amountPaid = $grandTotal;
            $set('amount_paid', $amountPaid);
        }

        $set('credit_total', round(max($grandTotal - $amountPaid, 0), 2));
    }

    protected static function calculateItemTotal(Get $get): float
    {
        $quantity = (float) ($get('quantity') ?? 0);
        $unitPrice = (float) ($get('unit_price') ?? 0);

        return round($quantity * $unitPrice, 2);
    }

    protected static function formatCurrency(float $amount): string
    {
        return 'Rs ' . number_format($amount, 2);
    }
}

