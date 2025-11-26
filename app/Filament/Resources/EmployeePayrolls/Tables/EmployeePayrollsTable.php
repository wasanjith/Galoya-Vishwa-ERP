<?php

namespace App\Filament\Resources\EmployeePayrolls\Tables;

use App\Enums\PayrollPeriodType;
use App\Models\EmployeePayroll;
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

class EmployeePayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('period_type')
                    ->label('Period')
                    ->formatStateUsing(fn (?string $state): string => PayrollPeriodType::tryFrom((string) $state)?->label() ?? 'Unknown')
                    ->colors([
                        'info' => fn (?string $state): bool => $state === PayrollPeriodType::Monthly->value,
                        'success' => fn (?string $state): bool => $state === PayrollPeriodType::Daily->value,
                    ]),

                TextColumn::make('period_start_date')
                    ->label('Start')
                    ->date()
                    ->sortable(),

                TextColumn::make('period_end_date')
                    ->label('End')
                    ->date()
                    ->sortable(),

                TextColumn::make('working_days')
                    ->label('Records')
                    ->alignRight()
                    ->sortable()
                    ->tooltip('Attendance records counted for the period'),

                TextColumn::make('present_days')
                    ->label('Present')
                    ->alignRight()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('half_days')
                    ->label('Half')
                    ->alignRight()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('absent_days')
                    ->label('Absent')
                    ->alignRight()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('attendance_units')
                    ->label('Units')
                    ->alignRight()
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 2)),

                TextColumn::make('calculated_salary')
                    ->label('Calculated Salary')
                    ->state(fn (EmployeePayroll $record): float => (float) $record->calculated_salary)
                    ->formatStateUsing(fn (float $state): string => Number::currency($state, 'LKR'))
                    ->alignRight()
                    ->weight('semibold'),
            ])
            ->filters([
                SelectFilter::make('employee_id')
                    ->label('Employee')
                    ->relationship('employee', 'name')
                    ->searchable(),

                SelectFilter::make('period_type')
                    ->label('Period Type')
                    ->options(PayrollPeriodType::options()),

                Filter::make('period_range')
                    ->form([
                        DatePicker::make('from')
                            ->label('From')
                            ->native(false),
                        DatePicker::make('until')
                            ->label('To')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $inner, string $date): Builder => $inner->whereDate('period_start_date', '>=', $date))
                            ->when($data['until'] ?? null, fn (Builder $inner, string $date): Builder => $inner->whereDate('period_end_date', '<=', $date));
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
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with('employee'))
            ->defaultSort('period_start_date', 'desc')
            ->striped();
    }
}


