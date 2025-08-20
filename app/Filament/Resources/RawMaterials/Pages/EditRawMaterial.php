<?php

namespace App\Filament\Resources\RawMaterials\Pages;

use App\Filament\Resources\RawMaterials\RawMaterialResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRawMaterial extends EditRecord
{
    protected static string $resource = RawMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->modalHeading('Delete Raw Material')
                ->modalDescription('Are you sure you want to delete this raw material? This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, delete raw material'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Raw Material updated successfully';
    }

    protected function getDeletedNotificationTitle(): ?string
    {
        return 'Raw Material deleted successfully';
    }
}
