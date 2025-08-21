<?php

namespace App\Filament\Resources\StockMovements\Pages;

use App\Filament\Resources\StockMovements\StockMovementResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStockMovement extends EditRecord
{
    protected static string $resource = StockMovementResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Remove the temporary form fields
        unset($data['category_filter']);
        
        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Add the category filter based on the product's category
        if (isset($data['product_id'])) {
            $product = \App\Models\Product::find($data['product_id']);
            if ($product) {
                $data['category_filter'] = $product->category;
            }
        }
        
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
