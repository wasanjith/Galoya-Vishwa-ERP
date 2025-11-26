<?php

namespace App\Filament\Resources\EmployeePayrolls;

use App\Filament\Resources\EmployeePayrolls\Pages\CreateEmployeePayroll;
use App\Filament\Resources\EmployeePayrolls\Pages\EditEmployeePayroll;
use App\Filament\Resources\EmployeePayrolls\Pages\ListEmployeePayrolls;
use App\Filament\Resources\EmployeePayrolls\Schemas\EmployeePayrollForm;
use App\Filament\Resources\EmployeePayrolls\Tables\EmployeePayrollsTable;
use App\Models\EmployeePayroll;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EmployeePayrollResource extends Resource
{
    protected static ?string $model = EmployeePayroll::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static UnitEnum|string|null $navigationGroup = 'Human Resources';

    protected static ?string $navigationLabel = 'Salaries';

    protected static ?string $modelLabel = 'Salary Calculation';

    protected static ?string $pluralModelLabel = 'Salary Calculations';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return EmployeePayrollForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeePayrollsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmployeePayrolls::route('/'),
            'create' => CreateEmployeePayroll::route('/create'),
            'edit' => EditEmployeePayroll::route('/{record}/edit'),
        ];
    }
}


