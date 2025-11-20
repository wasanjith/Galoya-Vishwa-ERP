<?php

namespace App\Filament\Resources\CreditSalePayments\Pages;

use App\Filament\Resources\CreditSalePayments\CreditSalePaymentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCreditSalePayment extends EditRecord
{
    protected static string $resource = CreditSalePaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->modalHeading('Delete Payment')
                ->modalDescription('Are you sure you want to delete this payment record?')
                ->modalSubmitActionLabel('Yes, delete payment'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Payment updated successfully';
    }

    protected function getDeletedNotificationTitle(): ?string
    {
        return 'Payment deleted successfully';
    }
}

