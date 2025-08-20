<?php

namespace App\Filament\Resources\RawMaterialStocks\Pages;

use App\Filament\Resources\RawMaterialStocks\RawMaterialStockResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRawMaterialStock extends CreateRecord
{
    protected static string $resource = RawMaterialStockResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['last_updated'] = now();
        
        return $data;
    }
}
