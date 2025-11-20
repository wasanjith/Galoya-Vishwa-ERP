<?php

namespace App\Filament\Resources\CreditSalePayments\Tables;

use App\Models\Route;
use App\Models\Store;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CreditSalePaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('creditSale.invoice_number')
                    ->label('Invoice #')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('store.name')
                    ->label('Store')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('store.route.route')
                    ->label('Route')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('paid_date')
                    ->label('Paid Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => 'Rs ' . number_format((float) $state, 2))
                    ->sortable(),

                TextColumn::make('reference')
                    ->label('Reference')
                    ->toggleable()
                    ->limit(25),

                TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('store.route_id')
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
            ->defaultSort('paid_date', 'desc')
            ->paginated([10, 25, 50])
            ->striped();
    }
}

