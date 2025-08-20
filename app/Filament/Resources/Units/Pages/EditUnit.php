<?php

namespace App\Filament\Resources\Units\Pages;

use App\Filament\Resources\Units\UnitResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUnit extends EditRecord
{
    protected static string $resource = UnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->modalHeading('Delete Unit')
                ->modalDescription('Are you sure you want to delete this unit? This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, delete unit'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Unit updated successfully';
    }

    protected function getDeletedNotificationTitle(): ?string
    {
        return 'Unit deleted successfully';
    }
}
