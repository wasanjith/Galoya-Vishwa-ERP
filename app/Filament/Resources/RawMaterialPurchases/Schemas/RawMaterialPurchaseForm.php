<?php

namespace App\Filament\Resources\RawMaterialPurchases\Schemas;

use App\Models\RawMaterial;
use App\Models\Supplier;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RawMaterialPurchaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('raw_material_id')
                    ->label('Raw Material')
                    ->relationship('rawMaterial', 'name')
                    ->options(RawMaterial::where('is_active', true)->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->helperText('Select the raw material being purchased'),

                Select::make('supplier_id')
                    ->label('Supplier')
                    ->relationship('supplier', 'name')
                    ->options(Supplier::where('is_active', true)->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->helperText('Select the supplier for this purchase'),

                TextInput::make('invoice_number')
                    ->label('Invoice Number')
                    ->placeholder('e.g., INV-2024-001')
                    ->maxLength(255)
                    ->helperText('The invoice number from the supplier'),

                TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->inputMode('decimal')
                    ->step(0.001)
                    ->minValue(0)
                    ->required()
                    ->helperText('The quantity of raw material purchased'),

                TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->numeric()
                    ->inputMode('decimal')
                    ->step(0.01)
                    ->minValue(0)
                    ->required()
                    ->prefix('Rs.')
                    ->helperText('The total amount of the purchase in Sri Lankan Rupees'),

                Select::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'partial' => 'Partial',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                    ])
                    ->default('pending')
                    ->required()
                    ->helperText('Current payment status of this purchase'),

                TextInput::make('amount_paid')
                    ->label('Amount Paid')
                    ->numeric()
                    ->inputMode('decimal')
                    ->step(0.01)
                    ->minValue(0)
                    ->default(0)
                    ->prefix('Rs.')
                    ->helperText('Amount already paid for this purchase'),

                TextInput::make('balance_amount')
                    ->label('Balance Amount')
                    ->numeric()
                    ->inputMode('decimal')
                    ->step(0.01)
                    ->minValue(0)
                    ->default(0)
                    ->prefix('Rs.')
                    ->helperText('Remaining balance amount to be paid (auto-calculated)')
                    ->disabled()
                    ->dehydrated(),

                FileUpload::make('invoice_soft_copy')
                    ->label('Invoice File')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->maxSize(10240)
                    ->disk('public')
                    ->directory('invoices')
                    ->preserveFilenames()
                    ->downloadable()
                    ->openable()
                    ->helperText('Upload the invoice document (PDF or image, max 10MB)'),

                TextInput::make('invoice_file_type')
                    ->label('File Type')
                    ->placeholder('e.g., PDF, JPG, PNG')
                    ->maxLength(50)
                    ->helperText('Type of the uploaded invoice file (auto-detected)')
                    ->disabled()
                    ->dehydrated(),
            ]);
    }
}
