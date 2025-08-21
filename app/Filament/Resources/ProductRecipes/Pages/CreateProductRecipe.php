<?php

namespace App\Filament\Resources\ProductRecipes\Pages;

use App\Filament\Resources\ProductRecipes\ProductRecipeResource;
use App\Models\ProductRecipe;
use Filament\Resources\Pages\CreateRecord;

class CreateProductRecipe extends CreateRecord
{
    protected static string $resource = ProductRecipeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Transform the repeater data to the format expected by the model
        if (isset($data['recipe_ingredients'])) {
            $rawMaterialIds = [];
            $quantities = [];
            
            foreach ($data['recipe_ingredients'] as $ingredient) {
                if (isset($ingredient['raw_material_id']) && isset($ingredient['quantity_required'])) {
                    $rawMaterialIds[] = $ingredient['raw_material_id'];
                    $quantities[] = $ingredient['quantity_required'];
                }
            }
            
            $data['raw_material_id'] = $rawMaterialIds;
            $data['quantity_required'] = $quantities;
        }
        
        // Remove the temporary form fields
        unset($data['category_filter'], $data['recipe_ingredients']);
        
        return $data;
    }
}
