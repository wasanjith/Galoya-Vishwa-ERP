<?php

namespace App\Providers;

use App\Models\CreditSale;
use App\Models\RawMaterialPurchase;
use App\Models\StockMovement;
use App\Observers\CreditSaleObserver;
use App\Observers\RawMaterialPurchaseObserver;
use App\Observers\StockMovementObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        StockMovement::observe(StockMovementObserver::class);
        RawMaterialPurchase::observe(RawMaterialPurchaseObserver::class);
        CreditSale::observe(CreditSaleObserver::class);
    }
}
