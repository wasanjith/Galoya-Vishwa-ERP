<?php

namespace App\Filament\Resources\RawMaterialCategories;

use App\Filament\Resources\RawMaterialCategories\Pages\CreateRawMaterialCategory;
use App\Filament\Resources\RawMaterialCategories\Pages\EditRawMaterialCategory;
use App\Filament\Resources\RawMaterialCategories\Pages\ListRawMaterialCategories;
use App\Filament\Resources\RawMaterialCategories\Schemas\RawMaterialCategoryForm;
use App\Filament\Resources\RawMaterialCategories\Tables\RawMaterialCategoriesTable;
use App\Models\RawMaterialCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RawMaterialCategoryResource extends Resource
{
    protected static ?string $model = RawMaterialCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static UnitEnum|string|null $navigationGroup = 'Raw Material Management';

    protected static ?string $navigationLabel = 'Raw Material Categories';

    protected static ?string $modelLabel = 'Raw Material Category';

    protected static ?string $pluralModelLabel = 'Raw Material Categories';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return RawMaterialCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RawMaterialCategoriesTable::configure($table);
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
            'index' => ListRawMaterialCategories::route('/'),
            'create' => CreateRawMaterialCategory::route('/create'),
            'edit' => EditRawMaterialCategory::route('/{record}/edit'),
        ];
    }
}
