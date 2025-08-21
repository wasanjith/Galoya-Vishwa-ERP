<?php

namespace App\Filament\Resources\StockMovements\Pages;

use App\Filament\Resources\StockMovements\StockMovementResource;
use App\Models\StockMovement;
use Filament\Resources\Pages\CreateRecord;

class CreateStockMovement extends CreateRecord
{
    protected static string $resource = StockMovementResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Remove the temporary form fields
        unset($data['category_filter']);
        
        return $data;
    }

    protected function getFormData(): array
    {
        return [
            'movement_type' => 'purchase',
        ];
    }
}
