<?php

namespace App\Filament\Resources\RawMaterialStocks;

use App\Filament\Resources\RawMaterialStocks\Pages\CreateRawMaterialStock;
use App\Filament\Resources\RawMaterialStocks\Pages\EditRawMaterialStock;
use App\Filament\Resources\RawMaterialStocks\Pages\ListRawMaterialStocks;
use App\Filament\Resources\RawMaterialStocks\Schemas\RawMaterialStockForm;
use App\Filament\Resources\RawMaterialStocks\Tables\RawMaterialStocksTable;
use App\Models\RawMaterialStock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RawMaterialStockResource extends Resource
{
    protected static ?string $model = RawMaterialStock::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static UnitEnum|string|null $navigationGroup = 'Raw Material Management';

    protected static ?string $navigationLabel = 'Raw Material Stocks';

    protected static ?string $modelLabel = 'Raw Material Stock';

    protected static ?string $pluralModelLabel = 'Raw Material Stocks';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return RawMaterialStockForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RawMaterialStocksTable::configure($table);
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
            'index' => ListRawMaterialStocks::route('/'),
            'create' => CreateRawMaterialStock::route('/create'),
            'edit' => EditRawMaterialStock::route('/{record}/edit'),
        ];
    }
}
