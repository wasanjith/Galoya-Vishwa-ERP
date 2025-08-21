<?php

namespace App\Filament\Resources\ProductRecipes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductRecipesTable
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
                
                TextColumn::make('ingredient_count')
                    ->label('Ingredients')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                
                
                
                TextColumn::make('total_cost')
                    ->label('Total Cost')
                    ->money('INR')
                    ->sortable()
                    ->badge()
                    ->color('warning'),
                
            ])
            ->filters([
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
            ->defaultSort('product.name', 'asc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
