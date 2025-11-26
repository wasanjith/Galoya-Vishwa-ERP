<?php

namespace App\Filament\Resources\EmployeePayrolls\Pages;

use App\Filament\Resources\EmployeePayrolls\EmployeePayrollResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployeePayrolls extends ListRecords
{
    protected static string $resource = EmployeePayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Generate Salary')
                ->icon('heroicon-o-currency-dollar'),
        ];
    }
}


