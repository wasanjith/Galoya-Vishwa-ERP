<?php

namespace App\Filament\Resources\CreditSalePayments;

use App\Filament\Resources\CreditSalePayments\Pages\CreateCreditSalePayment;
use App\Filament\Resources\CreditSalePayments\Pages\EditCreditSalePayment;
use App\Filament\Resources\CreditSalePayments\Pages\ListCreditSalePayments;
use App\Filament\Resources\CreditSalePayments\Schemas\CreditSalePaymentForm;
use App\Filament\Resources\CreditSalePayments\Tables\CreditSalePaymentsTable;
use App\Models\CreditSalePayment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CreditSalePaymentResource extends Resource
{
    protected static ?string $model = CreditSalePayment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyRupee;

    protected static UnitEnum|string|null $navigationGroup = 'Sales & Distribution';

    protected static ?string $navigationLabel = 'Credit Payments';

    protected static ?string $modelLabel = 'Credit Payment';

    protected static ?string $pluralModelLabel = 'Credit Payments';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return CreditSalePaymentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CreditSalePaymentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCreditSalePayments::route('/'),
            'create' => CreateCreditSalePayment::route('/create'),
            'edit' => EditCreditSalePayment::route('/{record}/edit'),
        ];
    }
}

