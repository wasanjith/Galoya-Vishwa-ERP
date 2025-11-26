<?php

namespace App\Filament\Resources\EmployeeAttendances\Tables;

use App\Enums\AttendanceStatus;
use App\Models\EmployeeAttendance;
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

class EmployeeAttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('attendance_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (?string $state): string => AttendanceStatus::tryFrom((string) $state)?->label() ?? 'Unknown')
                    ->colors([
                        'success' => fn (?string $state): bool => $state === AttendanceStatus::Present->value,
                        'warning' => fn (?string $state): bool => $state === AttendanceStatus::HalfDay->value,
                        'danger' => fn (?string $state): bool => $state === AttendanceStatus::Absent->value,
                        'info' => fn (?string $state): bool => $state === AttendanceStatus::Leave->value,
                    ]),

                TextColumn::make('worked_hours')
                    ->label('Hours')
                    ->state(fn (EmployeeAttendance $record): float => (float) $record->worked_hours)
                    ->formatStateUsing(fn (float $state): string => number_format($state, 2))
                    ->alignRight()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('daily_pay')
                    ->label('Salary (Daily)')
                    ->state(fn (EmployeeAttendance $record): float => $record->dailyPayout())
                    ->formatStateUsing(fn (float $state): string => Number::currency($state, 'LKR'))
                    ->alignRight()
                    ->description('Based on effective attendance units'),
            ])
            ->filters([
                SelectFilter::make('employee_id')
                    ->label('Employee')
                    ->relationship('employee', 'name')
                    ->searchable(),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options(AttendanceStatus::options()),

                Filter::make('attendance_date')
                    ->label('Date range')
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
                            ->when($data['from'] ?? null, fn (Builder $inner, string $date): Builder => $inner->whereDate('attendance_date', '>=', $date))
                            ->when($data['until'] ?? null, fn (Builder $inner, string $date): Builder => $inner->whereDate('attendance_date', '<=', $date));
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
            ->defaultSort('attendance_date', 'desc')
            ->striped();
    }
}


