<?php

namespace App\Filament\Resources\RawMaterialPurchases\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Table;

class RawMaterialPurchasesTable
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

                TextColumn::make('rawMaterial.name')
                    ->label('Raw Material')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->sortable()
                    ->searchable()
                    ->limit(20),

                TextColumn::make('invoice_number')
                    ->label('Invoice Number')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->numeric(
                        decimalPlaces: 3,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->money('LKR')
                    ->sortable()
                    ->weight('bold'),

                BadgeColumn::make('payment_status')
                    ->label('Payment Status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'partial',
                        'success' => 'paid',
                        'danger' => 'overdue',
                    ])
                    ->sortable(),

                TextColumn::make('amount_paid')
                    ->label('Amount Paid')
                    ->money('LKR')
                    ->sortable(),

                TextColumn::make('balance_amount')
                    ->label('Balance')
                    ->money('LKR')
                    ->sortable()
                    ->color(fn (string $state): string => match (true) {
                        $state > 0 => 'danger',
                        default => 'success',
                    }),

                TextColumn::make('payment_percentage')
                    ->label('Payment %')
                    ->numeric(
                        decimalPlaces: 1,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->suffix('%')
                    ->sortable()
                    ->color(fn (string $state): string => match (true) {
                        $state >= 100 => 'success',
                        $state >= 50 => 'info',
                        $state > 0 => 'warning',
                        default => 'danger',
                    }),

                TextColumn::make('invoice_soft_copy')
                    ->label('Invoice')
                    ->formatStateUsing(fn (string $state = null): string => $state ? 'View Invoice' : 'No Invoice')
                    ->url(fn ($record): string => $record->invoice_soft_copy ? asset('storage/' . $record->invoice_soft_copy) : '')
                    ->openUrlInNewTab()
                    ->color('info')
                    ->icon('heroicon-o-document')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn ($record): bool => !empty($record->invoice_soft_copy)),

                TextColumn::make('created_at')
                    ->label('Purchase Date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'partial' => 'Partial',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                    ]),

                SelectFilter::make('raw_material_id')
                    ->label('Raw Material')
                    ->relationship('rawMaterial', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('supplier_id')
                    ->label('Supplier')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('with_balance')
                    ->label('With Outstanding Balance')
                    ->query(fn (Builder $query): Builder => $query->where('balance_amount', '>', 0))
                    ->toggle(),

                Filter::make('fully_paid')
                    ->label('Fully Paid')
                    ->query(fn (Builder $query): Builder => $query->where('payment_status', 'paid'))
                    ->toggle(),
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
