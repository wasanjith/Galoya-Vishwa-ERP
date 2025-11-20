<?php

namespace App\Filament\Resources\CreditSalePayments\Schemas;

use App\Models\CreditSale;
use App\Models\Route;
use App\Models\Store;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class CreditSalePaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Select Invoice')
                    ->description('Pick the route, store, and outstanding invoice you are collecting for.')
                    ->schema([
                        Select::make('route_filter')
                            ->label('Route')
                            ->options(fn () => Route::query()->orderBy('route')->pluck('route', 'id')->all())
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set): void {
                                $set('store_id', null);
                                $set('credit_sale_id', null);
                            })
                            ->dehydrated(false),

                        Select::make('store_id')
                            ->label('Store')
                            ->options(function (Get $get): array {
                                $routeId = $get('route_filter');

                                $query = Store::query()->orderBy('name');

                                if ($routeId) {
                                    $query->where('route_id', $routeId);
                                }

                                return $query->pluck('name', 'id')->all();
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (Set $set): void {
                                $set('credit_sale_id', null);
                            }),

                        Select::make('credit_sale_id')
                            ->label('Invoice')
                            ->options(function (Get $get): array {
                                $storeId = $get('store_id');

                                if (! $storeId) {
                                    return [];
                                }

                                return CreditSale::query()
                                    ->where('store_id', $storeId)
                                    ->orderByDesc('sale_date')
                                    ->get()
                                    ->mapWithKeys(function (CreditSale $sale) {
                                        $label = sprintf(
                                            '%s • %s • %s due',
                                            $sale->invoice_number,
                                            optional($sale->sale_date)->format('M d'),
                                            self::formatCurrency($sale->credit_total)
                                        );

                                        return [$sale->id => $label];
                                    })
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn (Get $get): bool => blank($get('store_id')))
                            ->helperText('Invoices are shown newest first.')
                            ->reactive(),

                        Placeholder::make('invoice_snapshot')
                            ->label('Invoice Snapshot')
                            ->content(fn (Get $get): string => self::invoiceSnapshot($get))
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'text-sm text-gray-600']),
                    ])
                    ->columns([
                        'sm' => 2,
                        'lg' => 3,
                    ]),

                Section::make('Payment Details')
                    ->schema([
                        DatePicker::make('paid_date')
                            ->label('Paid Date')
                            ->default(now())
                            ->required(),

                        TextInput::make('amount')
                            ->label('Amount')
                            ->prefix('Rs')
                            ->numeric()
                            ->required()
                            ->minValue(0.01)
                            ->maxValue(fn (Get $get) => self::getOutstanding($get))
                            ->helperText(fn (Get $get) => 'Outstanding: ' . self::formatCurrency(self::getOutstanding($get)))
                            ->reactive(),

                        TextInput::make('reference')
                            ->label('Reference / Slip #')
                            ->placeholder('e.g., Bank slip, cash receipt...')
                            ->maxLength(255),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->placeholder('Add any quick notes about this payment (optional).')
                            ->columnSpanFull(),
                    ])
                    ->columns([
                        'sm' => 2,
                        'lg' => 2,
                    ]),
            ]);
    }

    protected static function getOutstanding(Get $get): float
    {
        $saleId = $get('credit_sale_id');

        if (! $saleId) {
            return 0;
        }

        return (float) optional(CreditSale::find($saleId))->credit_total ?? 0;
    }

    protected static function invoiceSnapshot(Get $get): string
    {
        $saleId = $get('credit_sale_id');

        if (! $saleId) {
            return 'Select an invoice to see total, paid, and outstanding amounts.';
        }

        $sale = CreditSale::find($saleId);

        if (! $sale) {
            return 'Invoice not found.';
        }

        return sprintf(
            'Invoice %s · Grand Total %s · Paid %s · Outstanding %s',
            $sale->invoice_number,
            self::formatCurrency($sale->grand_total),
            self::formatCurrency($sale->amount_paid),
            self::formatCurrency($sale->credit_total)
        );
    }

    protected static function formatCurrency(float $amount): string
    {
        return 'Rs ' . number_format($amount, 2);
    }
}

