<?php

namespace App\Filament\Resources\Stores\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StoresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Shop Name')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('address')
                    ->label('Address')
                    ->limit(35)
                    ->wrap()
                    ->toggleable()
                    ->placeholder('—'),

                TextColumn::make('phone')
                    ->label('Mobile Number')
                    ->icon('heroicon-o-phone')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('current_liabilities')
                    ->label('Liabilities')
                    ->sortable()
                    ->prefix('Rs ')
                    ->alignRight()
                    ->formatStateUsing(fn (?string $state): string => $state !== null ? number_format((float) $state, 2) : number_format(0, 2)),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->filters([
                SelectFilter::make('route_id')
                    ->label('Route')
                    ->relationship('route', 'route')
                    ->searchable()
                    ->preload()
                    ->placeholder('All routes')
                    ->default(fn () => request()->integer('route') ? (string) request()->integer('route') : null),
            ])
            ->defaultSort('name')
            ->striped();
    }
}

