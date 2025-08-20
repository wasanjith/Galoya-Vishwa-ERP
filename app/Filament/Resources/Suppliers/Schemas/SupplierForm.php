<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Supplier Name')
                    ->placeholder('e.g., ABC Corporation, XYZ Industries')
                    ->required()
                    ->maxLength(255)
                    ->helperText('The name of the supplier or company'),

                TextInput::make('phone')
                    ->label('Phone Number')
                    ->tel()
                    ->placeholder('e.g., +1-555-123-4567')
                    ->maxLength(255)
                    ->helperText('Contact phone number for the supplier'),

                Textarea::make('address')
                    ->label('Address')
                    ->placeholder('Enter the complete address of the supplier')
                    ->rows(3)
                    ->maxLength(1000)
                    ->helperText('Full address including street, city, state, and postal code'),

                Checkbox::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->helperText('Whether this supplier is currently active and available for business'),
            ]);
    }
}
