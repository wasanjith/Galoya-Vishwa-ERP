<?php

namespace App\Providers;

use App\Models\CreditSale;
use App\Models\RawMaterialPurchase;
use App\Models\StockMovement;
use App\Observers\CreditSaleObserver;
use App\Observers\RawMaterialPurchaseObserver;
use App\Observers\StockMovementObserver;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;
use Illuminate\Support\Facades\Vite;

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

        FilamentView::registerRenderHook(
            PanelsRenderHook::FOOTER,
            fn (): string => Vite::useBuildDirectory('build')->withEntryPoints(['resources/css/app.css'])->toHtml(),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE,
            static function (): string {
                if (! request()->routeIs('filament.admin.resources.routes.index')) {
                    return '';
                }

                if (! view()->exists('routescard')) {
                    return '';
                }

                return view('routescard')->render();
            },
        );

   
        FilamentView::registerRenderHook(
            PanelsRenderHook::FOOTER,
            fn(): View=> view('footer'),
        );
    }
}
