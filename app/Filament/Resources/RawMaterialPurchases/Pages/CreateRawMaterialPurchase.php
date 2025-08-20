<?php

namespace App\Filament\Resources\RawMaterialPurchases\Pages;

use App\Filament\Resources\RawMaterialPurchases\RawMaterialPurchaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRawMaterialPurchase extends CreateRecord
{
    protected static string $resource = RawMaterialPurchaseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
