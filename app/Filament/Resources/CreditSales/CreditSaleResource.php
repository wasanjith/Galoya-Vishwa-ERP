<?php

namespace App\Filament\Resources\CreditSales;

use App\Filament\Resources\CreditSales\Pages\CreateCreditSale;
use App\Filament\Resources\CreditSales\Pages\EditCreditSale;
use App\Filament\Resources\CreditSales\Pages\ListCreditSales;
use App\Filament\Resources\CreditSales\Schemas\CreditSaleForm;
use App\Filament\Resources\CreditSales\Tables\CreditSalesTable;
use App\Models\CreditSale;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CreditSaleResource extends Resource
{
    protected static ?string $model = CreditSale::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static UnitEnum|string|null $navigationGroup = 'Sales & Distribution';

    protected static ?string $navigationLabel = 'Credit Sales';

    protected static ?string $modelLabel = 'Credit Sale';

    protected static ?string $pluralModelLabel = 'Credit Sales';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return CreditSaleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CreditSalesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCreditSales::route('/'),
            'create' => CreateCreditSale::route('/create'),
            'edit' => EditCreditSale::route('/{record}/edit'),
        ];
    }
}

