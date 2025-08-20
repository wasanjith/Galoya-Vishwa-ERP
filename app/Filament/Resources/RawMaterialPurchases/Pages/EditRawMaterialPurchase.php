<?php

namespace App\Filament\Resources\RawMaterialPurchases\Pages;

use App\Filament\Resources\RawMaterialPurchases\RawMaterialPurchaseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRawMaterialPurchase extends EditRecord
{
    protected static string $resource = RawMaterialPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
