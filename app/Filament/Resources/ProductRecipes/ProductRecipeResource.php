<?php

namespace App\Filament\Resources\ProductRecipes;

use App\Filament\Resources\ProductRecipes\Pages\CreateProductRecipe;
use App\Filament\Resources\ProductRecipes\Pages\EditProductRecipe;
use App\Filament\Resources\ProductRecipes\Pages\ListProductRecipes;
use App\Filament\Resources\ProductRecipes\Schemas\ProductRecipeForm;
use App\Filament\Resources\ProductRecipes\Tables\ProductRecipesTable;
use App\Models\ProductRecipe;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProductRecipeResource extends Resource
{
    protected static ?string $model = ProductRecipe::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static UnitEnum|string|null $navigationGroup = 'Product Management';

    protected static ?string $navigationLabel = 'Product Recipes';

    protected static ?string $modelLabel = 'Product Recipe';

    protected static ?string $pluralModelLabel = 'Product Recipes';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return ProductRecipeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductRecipesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductRecipes::route('/'),
            'create' => CreateProductRecipe::route('/create'),
            'edit' => EditProductRecipe::route('/{record}/edit'),
        ];
    }
}
