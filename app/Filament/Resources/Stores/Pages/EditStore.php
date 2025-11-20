<?php

namespace App\Filament\Resources\Stores\Pages;

use App\Filament\Resources\Stores\StoreResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStore extends EditRecord
{
    protected static string $resource = StoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->modalHeading('Delete Store')
                ->modalDescription('Are you sure you want to delete this store? This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, delete store'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Store updated successfully';
    }

    protected function getDeletedNotificationTitle(): ?string
    {
        return 'Store deleted successfully';
    }
}

