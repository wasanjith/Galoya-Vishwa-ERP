<?php

namespace App\Filament\Resources\VehicleMaintenances\Tables;

use App\Enums\VehicleMaintenanceType;
use App\Models\VehicleMaintenance;
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

class VehicleMaintenancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vehicle.name')
                    ->label('Vehicle')
                    ->description(fn (VehicleMaintenance $record): string => $record->vehicle?->number_plate ?? '—')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('maintenance_type')
                    ->label('Type')
                    ->formatStateUsing(fn (?string $state): string => VehicleMaintenanceType::tryFrom((string) $state)?->label() ?? 'Unknown')
                    ->colors([
                        'info' => fn (?string $state): bool => $state === VehicleMaintenanceType::Service->value,
                        'warning' => fn (?string $state): bool => $state === VehicleMaintenanceType::Tyre->value,
                        'danger' => fn (?string $state): bool => $state === VehicleMaintenanceType::Repair->value,
                        'gray' => fn (?string $state): bool => $state === VehicleMaintenanceType::Inspection->value,
                    ]),

                TextColumn::make('performed_at')
                    ->label('Performed On')
                    ->date()
                    ->sortable(),

                TextColumn::make('driver.name')
                    ->label('Driver')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('cost')
                    ->label('Cost')
                    ->state(fn (VehicleMaintenance $record): float => (float) $record->cost)
                    ->formatStateUsing(fn (float $state): string => Number::currency($state, 'LKR'))
                    ->alignRight(),

                TextColumn::make('last_service_date')
                    ->label('Last Service')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('next_service_date')
                    ->label('Next Service')
                    ->date()
                    ->description('Synced to vehicle'),

                TextColumn::make('last_tyre_replace_date')
                    ->label('Last Tyre Replace')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('vehicle_id')
                    ->label('Vehicle')
                    ->relationship('vehicle', 'name')
                    ->searchable(),

                SelectFilter::make('maintenance_type')
                    ->label('Type')
                    ->options(VehicleMaintenanceType::options()),

                Filter::make('performed_at')
                    ->label('Performed Date')
                    ->form([
                        DatePicker::make('from')->native(false),
                        DatePicker::make('until')->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $inner, string $date): Builder => $inner->whereDate('performed_at', '>=', $date))
                            ->when($data['until'] ?? null, fn (Builder $inner, string $date): Builder => $inner->whereDate('performed_at', '<=', $date));
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
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['vehicle', 'driver']))
            ->defaultSort('performed_at', 'desc')
            ->striped();
    }
}


