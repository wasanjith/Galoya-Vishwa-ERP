<?php

namespace App\Filament\Resources\CreditSalePayments\Pages;

use App\Filament\Resources\CreditSalePayments\CreditSalePaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCreditSalePayment extends CreateRecord
{
    protected static string $resource = CreditSalePaymentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Payment recorded successfully';
    }
}

