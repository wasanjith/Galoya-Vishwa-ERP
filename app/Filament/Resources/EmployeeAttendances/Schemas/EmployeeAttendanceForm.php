<?php

namespace App\Filament\Resources\EmployeeAttendances\Schemas;

use App\Enums\AttendanceStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class EmployeeAttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Attendance Details')
                    ->schema([
                        Select::make('employee_id')
                            ->label('Employee')
                            ->relationship('employee', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Select employee'),

                        DatePicker::make('attendance_date')
                            ->label('Date')
                            ->required()
                            ->default(now())
                            ->native(false),

                        Select::make('status')
                            ->label('Status')
                            ->options(AttendanceStatus::options())
                            ->required()
                            ->native(false),

                        TimePicker::make('check_in_at')
                            ->label('Check-in')
                            ->seconds(false),

                        TimePicker::make('check_out_at')
                            ->label('Check-out')
                            ->seconds(false),

                        TextInput::make('worked_hours')
                            ->label('Worked Hours')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(24)
                            ->step(0.25)
                            ->default(0),
                    ])
                    ->columns(3),

                Section::make('Notes')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Remarks')
                            ->rows(3)
                            ->placeholder('Optional remarks or deviations'),
                    ]),
            ]);
    }
}


