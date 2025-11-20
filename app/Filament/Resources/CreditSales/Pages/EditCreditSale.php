<?php

namespace App\Filament\Resources\CreditSales\Pages;

use App\Filament\Resources\CreditSales\CreditSaleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCreditSale extends EditRecord
{
    protected static string $resource = CreditSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->modalHeading('Delete Credit Sale')
                ->modalDescription('This will permanently remove the credit sale record.')
                ->modalSubmitActionLabel('Yes, delete it'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Credit sale updated successfully';
    }

    protected function getDeletedNotificationTitle(): ?string
    {
        return 'Credit sale deleted successfully';
    }
}

