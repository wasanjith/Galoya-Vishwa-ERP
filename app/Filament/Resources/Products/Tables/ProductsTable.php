<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
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
                
                TextColumn::make('name')
                    ->label('Product Name')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->limit(50),
                
                TextColumn::make('product_code')
                    ->label('Product Code')
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('info'),
                
                TextColumn::make('category')
                    ->label('Category')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('success'),
                
                
                
                
                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                
            ])
            ->filters([
                SelectFilter::make('category')
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
                
                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All Products')
                    ->trueLabel('Active Products')
                    ->falseLabel('Inactive Products'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name', 'asc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
