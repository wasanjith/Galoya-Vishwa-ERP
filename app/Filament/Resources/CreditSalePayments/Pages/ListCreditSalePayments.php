<?php

namespace App\Filament\Resources\CreditSalePayments\Pages;

use App\Filament\Resources\CreditSalePayments\CreditSalePaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCreditSalePayments extends ListRecords
{
    protected static string $resource = CreditSalePaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add Payment')
                ->icon('heroicon-o-plus'),
        ];
    }
}

