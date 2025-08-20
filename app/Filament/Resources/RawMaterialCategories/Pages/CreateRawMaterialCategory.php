<?php

namespace App\Filament\Resources\RawMaterialCategories\Pages;

use App\Filament\Resources\RawMaterialCategories\RawMaterialCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRawMaterialCategory extends CreateRecord
{
    protected static string $resource = RawMaterialCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Raw Material Category created successfully';
    }
}
