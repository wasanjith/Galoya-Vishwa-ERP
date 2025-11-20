<?php

namespace App\Observers;

use App\Models\RawMaterialPurchase;
use App\Models\RawMaterialStock;

class RawMaterialPurchaseObserver
{
    /**
     * Handle the RawMaterialPurchase "created" event.
     */
    public function created(RawMaterialPurchase $purchase): void
    {
        $this->updateRawMaterialStock($purchase);
    }

    /**
     * Handle the RawMaterialPurchase "updated" event.
     */
    public function updated(RawMaterialPurchase $purchase): void
    {
        // Get the original quantity before the update
        $originalQuantity = $purchase->getOriginal('quantity') ?? 0;
        $newQuantity = $purchase->quantity;
        $quantityDifference = $newQuantity - $originalQuantity;

        // If quantity changed, update stock accordingly
        if ($quantityDifference != 0) {
            $this->updateRawMaterialStock($purchase, $quantityDifference);
        }
    }

    /**
     * Handle the RawMaterialPurchase "deleted" event.
     */
    public function deleted(RawMaterialPurchase $purchase): void
    {
        // When a purchase is deleted, we need to reduce the stock by the purchased quantity
        $this->updateRawMaterialStock($purchase, -$purchase->quantity);
    }

    /**
     * Update the raw material stock based on the purchase.
     *
     * @param RawMaterialPurchase $purchase
     * @param float|null $quantity Override quantity (used for updates/deletions)
     */
    protected function updateRawMaterialStock(RawMaterialPurchase $purchase, float $quantity = null): void
    {
        // Get or create the raw material stock record
        $rawMaterialStock = RawMaterialStock::getOrCreateForRawMaterial($purchase->raw_material_id);

        // Use the provided quantity or default to the purchase quantity
        $quantityChange = $quantity ?? $purchase->quantity;

        // Add or remove stock based on the quantity change
        if ($quantityChange > 0) {
            $rawMaterialStock->addStock($quantityChange);
        } elseif ($quantityChange < 0) {
            $rawMaterialStock->removeStock(abs($quantityChange));
        }
    }
}