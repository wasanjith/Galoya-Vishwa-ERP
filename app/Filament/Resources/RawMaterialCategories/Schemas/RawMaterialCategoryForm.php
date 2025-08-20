<?php

namespace App\Filament\Resources\RawMaterialCategories\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RawMaterialCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Category Name')
                    ->placeholder('e.g., Metals, Plastics, Chemicals')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('The name of the raw material category'),
                
                Checkbox::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->helperText('Whether this category is currently active and available for use'),
            ]);
    }
}
