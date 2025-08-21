<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_code',
        'category',
        'selling_prices',
        'cost_price',
        'is_active',
    ];

    protected $casts = [
        'selling_prices' => 'array',
        'cost_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the product category that owns the product.
     */
    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category', 'name');
    }

    /**
     * Get the product stocks for this product.
     */
    public function productStocks(): HasMany
    {
        return $this->hasMany(ProductStock::class);
    }

    /**
     * Get the recipe for this product.
     */
    public function recipe(): HasOne
    {
        return $this->hasOne(ProductRecipe::class);
    }

    /**
     * Get the stock movements for this product.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get the product category name.
     */
    public function getCategoryNameAttribute(): string
    {
        return $this->category;
    }

    /**
     * Get the primary selling price (first price in the array).
     */
    public function getPrimaryPriceAttribute(): ?float
    {
        if (is_array($this->selling_prices) && !empty($this->selling_prices)) {
            return array_values($this->selling_prices)[0];
        }
        return null;
    }

    /**
     * Check if the product is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get total available stock quantity.
     */
    public function getTotalStockAttribute(): int
    {
        return $this->productStocks()->sum('quantity_available');
    }

    /**
     * Get active stock (non-expired).
     */
    public function getActiveStockAttribute(): int
    {
        return $this->productStocks()
            ->where('status', '!=', 'expired')
            ->sum('quantity_available');
    }
}
