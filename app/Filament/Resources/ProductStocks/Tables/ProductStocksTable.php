<?php

namespace App\Filament\Resources\ProductStocks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductStocksTable
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
                
                TextColumn::make('batch_number')
                    ->label('Batch Number')
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('warning'),
                
                TextColumn::make('quantity_available')
                    ->label('Quantity')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                
                TextColumn::make('manufactured_date')
                    ->label('Manufactured')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('expiry_date')
                    ->label('Expiry Date')
                    ->date()
                    ->sortable()
                    ->color(fn ($record) => $record->isExpired() ? 'danger' : ($record->isNearExpiry() ? 'warning' : 'success')),
                
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'fresh',
                        'warning' => 'near_expiry',
                        'danger' => 'expired',
                    ])
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'fresh' => 'Fresh',
                        'near_expiry' => 'Near Expiry',
                        'expired' => 'Expired',
                    ])
                    ->placeholder('All Statuses'),
                
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
            ->defaultSort('expiry_date', 'asc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
