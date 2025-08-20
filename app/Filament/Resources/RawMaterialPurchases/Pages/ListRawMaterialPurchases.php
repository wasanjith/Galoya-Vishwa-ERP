<?php

namespace App\Filament\Resources\RawMaterialPurchases\Pages;

use App\Filament\Resources\RawMaterialPurchases\RawMaterialPurchaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRawMaterialPurchases extends ListRecords
{
    protected static string $resource = RawMaterialPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
