<?php

namespace App\Filament\Resources\CreditSales\Tables;

use App\Models\Route;
use App\Models\Store;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CreditSalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Invoice #')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('route.route')
                    ->label('Route')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('store.name')
                    ->label('Store')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('sale_date')
                    ->label('Sale Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('grand_total')
                    ->label('Grand Total')
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => 'Rs ' . number_format((float) $state, 2))
                    ->sortable(),

                TextColumn::make('amount_paid')
                    ->label('Paid Now')
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => 'Rs ' . number_format((float) $state, 2))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('credit_total')
                    ->label('Credit Balance')
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => 'Rs ' . number_format((float) $state, 2))
                    ->color(fn ($state) => ((float) $state) > 0 ? 'danger' : 'success')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('route_id')
                    ->label('Route')
                    ->options(fn () => Route::query()->orderBy('route')->pluck('route', 'id')->all()),

                SelectFilter::make('store_id')
                    ->label('Store')
                    ->options(fn () => Store::query()->orderBy('name')->pluck('name', 'id')->all()),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sale_date', 'desc')
            ->paginated([10, 25, 50])
            ->striped();
    }
}

