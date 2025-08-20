<?php

namespace App\Filament\Resources\RawMaterialPurchases;

use App\Filament\Resources\RawMaterialPurchases\Pages\CreateRawMaterialPurchase;
use App\Filament\Resources\RawMaterialPurchases\Pages\EditRawMaterialPurchase;
use App\Filament\Resources\RawMaterialPurchases\Pages\ListRawMaterialPurchases;
use App\Filament\Resources\RawMaterialPurchases\Schemas\RawMaterialPurchaseForm;
use App\Filament\Resources\RawMaterialPurchases\Tables\RawMaterialPurchasesTable;
use App\Models\RawMaterialPurchase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RawMaterialPurchaseResource extends Resource
{
    protected static ?string $model = RawMaterialPurchase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static UnitEnum|string|null $navigationGroup = 'Raw Material Management';

    protected static ?string $navigationLabel = 'Raw Material Purchases';

    protected static ?string $modelLabel = 'Raw Material Purchase';

    protected static ?string $pluralModelLabel = 'Raw Material Purchases';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return RawMaterialPurchaseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RawMaterialPurchasesTable::configure($table);
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
            'index' => ListRawMaterialPurchases::route('/'),
            'create' => CreateRawMaterialPurchase::route('/create'),
            'edit' => EditRawMaterialPurchase::route('/{record}/edit'),
        ];
    }
}
