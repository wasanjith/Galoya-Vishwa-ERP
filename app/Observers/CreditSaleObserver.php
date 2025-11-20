<?php

namespace App\Observers;

use App\Models\CreditSale;

class CreditSaleObserver
{
    public function created(CreditSale $creditSale): void
    {
        $this->adjustStoreLiability($creditSale, $creditSale->credit_total);
    }

    public function updated(CreditSale $creditSale): void
    {
        $previousTotal = (float) $creditSale->getOriginal('credit_total');
        $currentTotal = (float) $creditSale->credit_total;

        $difference = $currentTotal - $previousTotal;

        if ($difference !== 0.0) {
            $this->adjustStoreLiability($creditSale, $difference);
        }
    }

    public function deleted(CreditSale $creditSale): void
    {
        $this->adjustStoreLiability($creditSale, -1 * (float) $creditSale->credit_total);
    }

    protected function adjustStoreLiability(CreditSale $creditSale, float $amount): void
    {
        $store = $creditSale->store;

        if (! $store || $amount === 0.0) {
            return;
        }

        $store->update([
            'current_liabilities' => max(($store->current_liabilities ?? 0) + $amount, 0),
        ]);
    }
}

