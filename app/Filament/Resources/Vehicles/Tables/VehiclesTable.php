<?php

namespace App\Filament\Resources\Vehicles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Vehicle')
                    ->description(fn ($record): string => $record->number_plate)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('current_mileage')
                    ->label('Mileage')
                    ->state(fn ($record): float => (float) $record->current_mileage)
                    ->formatStateUsing(fn (float $state): string => Number::format($state).' km')
                    ->sortable()
                    ->alignRight(),

                BadgeColumn::make('tyre_condition')
                    ->label('Tyres')
                    ->formatStateUsing(function (?string $state): string {
                        return match ($state) {
                            'excellent' => 'Excellent',
                            'good' => 'Good',
                            'average' => 'Average',
                            'needs_attention' => 'Needs Attention',
                            default => 'Unknown',
                        };
                    })
                    ->colors([
                        'success' => fn (?string $state): bool => $state === 'excellent',
                        'info' => fn (?string $state): bool => $state === 'good',
                        'warning' => fn (?string $state): bool => $state === 'average',
                        'danger' => fn (?string $state): bool => $state === 'needs_attention',
                    ]),

                TextColumn::make('last_service_date')
                    ->label('Last Service')
                    ->date()
                    ->sortable(),

                TextColumn::make('next_service_date')
                    ->label('Next Service')
                    ->date()
                    ->sortable()
                    ->description('Planned date'),

                TextColumn::make('last_tyre_replace_date')
                    ->label('Last Tyre Change')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('net_worth')
                    ->label('Net Worth')
                    ->state(fn ($record): float => (float) $record->net_worth)
                    ->formatStateUsing(fn (float $state): string => Number::currency($state, 'LKR'))
                    ->alignRight(),

                TextColumn::make('diesel_efficiency')
                    ->label('Diesel Rate')
                    ->state(fn ($record): ?float => $record->diesel_efficiency ? (float) $record->diesel_efficiency : null)
                    ->formatStateUsing(fn (?float $state): string => $state !== null ? number_format($state, 1).' km/L' : '—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('tyre_condition')
                    ->label('Tyre Status')
                    ->options([
                        'excellent' => 'Excellent',
                        'good' => 'Good',
                        'average' => 'Average',
                        'needs_attention' => 'Needs Attention',
                    ]),

                Filter::make('next_service_due')
                    ->label('Service Range')
                    ->form([
                        DatePicker::make('from')->label('From')->native(false),
                        DatePicker::make('until')->label('Until')->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $inner, string $date): Builder => $inner->whereDate('next_service_date', '>=', $date))
                            ->when($data['until'] ?? null, fn (Builder $inner, string $date): Builder => $inner->whereDate('next_service_date', '<=', $date));
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name')
            ->striped();
    }
}


