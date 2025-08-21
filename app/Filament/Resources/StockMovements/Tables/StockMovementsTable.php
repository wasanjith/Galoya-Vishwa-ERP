<?php

namespace App\Filament\Resources\StockMovements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('product.name')
                    ->label('Product Name')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->limit(40),
                
                TextColumn::make('product.category')
                    ->label('Category')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('info'),
                
                BadgeColumn::make('movement_type')
                    ->label('Movement Type')
                    ->colors([
                        'success' => 'purchase',
                        'danger' => 'sale',
                        'info' => 'transfer',
                        'warning' => 'adjustment',
                        'success' => 'return',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'purchase' => 'Purchase',
                        'sale' => 'Sale',
                        'transfer' => 'Transfer',
                        'adjustment' => 'Adjustment',
                        'return' => 'Return',
                        default => $state,
                    })
                    ->sortable(),
                
                TextColumn::make('batch_number')
                    ->label('Batch')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('warning')
                    ->placeholder('No Batch'),
                
                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state < 0 ? 'danger' : 'success')
                    ->formatStateUsing(fn ($state) => $state < 0 ? '-' . number_format(abs($state), 0) : number_format($state, 0)),
                
                TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->notes;
                    })
                    ->placeholder('No notes'),
                
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('movement_type')
                    ->label('Movement Type')
                    ->options([
                        'purchase' => 'Purchase',
                        'sale' => 'Sale',
                        'transfer' => 'Transfer',
                        'adjustment' => 'Adjustment',
                        'return' => 'Return',
                    ])
                    ->placeholder('All Types'),
                
                SelectFilter::make('product.category')
                    ->label('Category')
                    ->options([
                        'Yoghurt' => 'Yoghurt',
                        'Ice Packet' => 'Ice Packet',
                        'Curd' => 'Curd',
                        'Ge Oil' => 'Ge Oil',
                        'Drinking Bottel' => 'Drinking Bottel',
                        'Popcilcles' => 'Popcilcles',
                        'Milk Toffe' => 'Milk Toffe',
                    ])
                    ->placeholder('All Categories'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
