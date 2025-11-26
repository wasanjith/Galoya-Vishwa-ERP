<?php

namespace App\Filament\Resources\Employees\Tables;

use App\Enums\EmployeeDepartment;
use App\Models\Employee;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('id_card_number')
                    ->label('ID Number')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                BadgeColumn::make('department')
                    ->label('Department')
                    ->formatStateUsing(fn (?string $state): string => EmployeeDepartment::tryFrom((string) $state)?->label() ?? 'N/A')
                    ->colors([
                        'primary' => fn (?string $state): bool => $state === EmployeeDepartment::Manufacturing->value,
                        'info' => fn (?string $state): bool => $state === EmployeeDepartment::Delivering->value,
                    ]),

                TextColumn::make('mobile_number')
                    ->label('Mobile')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                TextColumn::make('basic_salary')
                    ->label('Daily Salary')
                    ->money('LKR')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('department')
                    ->label('Department')
                    ->options(EmployeeDepartment::options()),
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


