<?php

namespace App\Observers;

use App\Models\ProductStock;
use App\Models\StockMovement;

class StockMovementObserver
{
    /**
     * Handle the StockMovement "created" event.
     */
    public function created(StockMovement $stockMovement): void
    {
        $this->updateProductStock($stockMovement);
    }

    /**
     * Handle the StockMovement "updated" event.
     */
    public function updated(StockMovement $stockMovement): void
    {
        // Get the original quantity before the update
        $originalQuantity = $stockMovement->getOriginal('quantity');
        $newQuantity = $stockMovement->quantity;
        $quantityDifference = $newQuantity - $originalQuantity;

        $this->updateProductStock($stockMovement, $quantityDifference);
    }

    /**
     * Handle the StockMovement "deleted" event.
     */
    public function deleted(StockMovement $stockMovement): void
    {
        // When a movement is deleted, we revert the stock change.
        // So, we pass the negative of the original quantity.
        $this->updateProductStock($stockMovement, -$stockMovement->quantity);
    }

    /**
     * Update the product stock based on the stock movement.
     *
     * @param StockMovement $stockMovement
     * @param float|null $quantity
     */
    protected function updateProductStock(StockMovement $stockMovement, float $quantity = null): void
    {
        $productStock = ProductStock::firstOrCreate(
            ['product_id' => $stockMovement->product_id],
            ['quantity_available' => 0]
        );

        // If quantity is not passed, use the quantity from the movement
        $quantityChange = $quantity ?? $stockMovement->quantity;

        if ($stockMovement->isStockIn()) {
            $productStock->increment('quantity_available', $quantityChange);
        } else {
            $productStock->decrement('quantity_available', $quantityChange);
        }
    }
}
