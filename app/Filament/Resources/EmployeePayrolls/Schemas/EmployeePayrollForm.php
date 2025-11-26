<?php

namespace App\Filament\Resources\EmployeePayrolls\Schemas;

use App\Enums\PayrollPeriodType;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Number;

class EmployeePayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Calculation Inputs')
                    ->schema([
                        Select::make('employee_id')
                            ->label('Employee')
                            ->relationship('employee', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Choose employee'),

                        Select::make('period_type')
                            ->label('Period Type')
                            ->options(PayrollPeriodType::options())
                            ->default(PayrollPeriodType::Monthly->value)
                            ->live()
                            ->required()
                            ->afterStateUpdated(function (Set $set, ?string $state, Get $get): void {
                                $start = $get('period_start_date');

                                if (! $start) {
                                    return;
                                }

                                if ($state === PayrollPeriodType::Daily->value) {
                                    $set('period_end_date', $start);

                                    return;
                                }

                                $startDate = Carbon::parse($start);
                                $set('period_start_date', $startDate->copy()->startOfMonth()->toDateString());
                                $set('period_end_date', $startDate->copy()->endOfMonth()->toDateString());
                            }),

                        DatePicker::make('period_start_date')
                            ->label('Start Date')
                            ->default(now()->startOfMonth())
                            ->native(false)
                            ->live()
                            ->required()
                            ->afterStateUpdated(function (Set $set, ?string $state, Get $get): void {
                                if ($get('period_type') === PayrollPeriodType::Daily->value) {
                                    $set('period_end_date', $state);

                                    return;
                                }

                                if (! $state) {
                                    return;
                                }

                                $start = Carbon::parse($state);

                                if ($get('period_type') === PayrollPeriodType::Monthly->value) {
                                    $set('period_start_date', $start->copy()->startOfMonth()->toDateString());
                                    $set('period_end_date', $start->copy()->endOfMonth()->toDateString());
                                }
                            }),

                        DatePicker::make('period_end_date')
                            ->label('End Date')
                            ->default(now()->endOfMonth())
                            ->native(false)
                            ->required()
                            ->rule('after_or_equal:period_start_date')
                            ->disabled(fn (Get $get): bool => $get('period_type') === PayrollPeriodType::Daily->value),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->placeholder('Optional remarks for payroll record'),
                    ])
                    ->columns(2),

                Section::make('Latest Snapshot')
                    ->schema([
                        Placeholder::make('summary')
                            ->label('Calculated Salary')
                            ->content(function ($record): string {
                                if (! $record) {
                                    return 'Salary will be calculated automatically using attendance once saved.';
                                }

                                return Number::currency((float) $record->calculated_salary, 'LKR')
                                    .' ('.$record->attendance_units.' units)';
                            }),
                    ]),
            ]);
    }
}


