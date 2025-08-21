<?php

namespace App\Filament\Resources\ProductRecipes\Pages;

use App\Filament\Resources\ProductRecipes\ProductRecipeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductRecipes extends ListRecords
{
    protected static string $resource = ProductRecipeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
