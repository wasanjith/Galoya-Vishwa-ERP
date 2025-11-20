<?php

namespace App\Filament\Resources\Routes\Pages;

use App\Filament\Resources\Routes\RouteResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRoute extends EditRecord
{
    protected static string $resource = RouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->modalHeading('Delete Route')
                ->modalDescription('Are you sure you want to delete this route? This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, delete route'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Route updated successfully';
    }

    protected function getDeletedNotificationTitle(): ?string
    {
        return 'Route deleted successfully';
    }
}

