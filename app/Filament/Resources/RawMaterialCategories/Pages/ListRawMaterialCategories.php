<?php

namespace App\Filament\Resources\RawMaterialCategories\Pages;

use App\Filament\Resources\RawMaterialCategories\RawMaterialCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRawMaterialCategories extends ListRecords
{
    protected static string $resource = RawMaterialCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add New Category')
                ->icon('heroicon-o-plus'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // You can add header widgets here if needed
        ];
    }
}
