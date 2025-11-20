<?php

namespace App\Filament\Resources\CreditSales\Pages;

use App\Filament\Resources\CreditSales\CreditSaleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCreditSale extends CreateRecord
{
    protected static string $resource = CreditSaleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Credit sale recorded successfully';
    }
}

