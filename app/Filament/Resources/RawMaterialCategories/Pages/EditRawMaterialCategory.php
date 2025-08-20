<?php

namespace App\Filament\Resources\RawMaterialCategories\Pages;

use App\Filament\Resources\RawMaterialCategories\RawMaterialCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRawMaterialCategory extends EditRecord
{
    protected static string $resource = RawMaterialCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->modalHeading('Delete Raw Material Category')
                ->modalDescription('Are you sure you want to delete this category? This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, delete category'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Raw Material Category updated successfully';
    }

    protected function getDeletedNotificationTitle(): ?string
    {
        return 'Raw Material Category deleted successfully';
    }
}
