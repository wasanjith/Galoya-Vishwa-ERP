<?php

namespace App\Filament\Resources\Employees\Schemas;

use App\Enums\EmployeeDepartment;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Employee Details')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->placeholder('e.g., Sandun Perera')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('age')
                            ->label('Age')
                            ->numeric()
                            ->minValue(16)
                            ->maxValue(70)
                            ->nullable(),

                        TextInput::make('id_card_number')
                            ->label('ID Card Number')
                            ->placeholder('NIC / Passport')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true),

                        Select::make('department')
                            ->label('Department')
                            ->options(EmployeeDepartment::options())
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),

                Section::make('Contact & Payroll')
                    ->schema([
                        Textarea::make('address')
                            ->label('Address')
                            ->rows(2)
                            ->maxLength(500)
                            ->placeholder('Optional short address for reference'),

                        TextInput::make('mobile_number')
                            ->label('Mobile Number')
                            ->tel()
                            ->maxLength(25)
                            ->placeholder('+94 77 123 4567'),

                        TextInput::make('basic_salary')
                            ->label('Basic Salary (Daily)')
                            ->helperText('Enter the employee\'s per-day rate; payroll multiplies by attendance units.')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->step(0.01)
                            ->prefix('Rs'),
                    ])
                    ->columns(2),
            ]);
    }
}


