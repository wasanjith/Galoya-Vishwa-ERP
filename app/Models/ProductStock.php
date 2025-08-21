<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ProductStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'batch_number',
        'quantity_available',
        'manufactured_date',
        'expiry_date',
        'status',
    ];

    protected $casts = [
        'manufactured_date' => 'date',
        'expiry_date' => 'date',
        'quantity_available' => 'integer',
    ];

    /**
     * Get the product that owns the stock.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product name.
     */
    public function getProductNameAttribute(): string
    {
        return $this->product->name ?? 'Unknown Product';
    }

    /**
     * Get the product category.
     */
    public function getProductCategoryAttribute(): string
    {
        return $this->product->category ?? 'Unknown Category';
    }

    /**
     * Check if stock is expired.
     */
    public function isExpired(): bool
    {
        return $this->expiry_date->isPast();
    }

    /**
     * Check if stock is near expiry (within 30 days).
     */
    public function isNearExpiry(): bool
    {
        return $this->expiry_date->diffInDays(now()) <= 30 && !$this->isExpired();
    }

    /**
     * Check if stock is fresh.
     */
    public function isFresh(): bool
    {
        return !$this->isExpired() && !$this->isNearExpiry();
    }

    /**
     * Update status based on expiry date.
     */
    public function updateStatus(): void
    {
        if ($this->isExpired()) {
            $this->update(['status' => 'expired']);
        } elseif ($this->isNearExpiry()) {
            $this->update(['status' => 'near_expiry']);
        } else {
            $this->update(['status' => 'fresh']);
        }
    }

    /**
     * Get days until expiry.
     */
    public function getDaysUntilExpiryAttribute(): int
    {
        return $this->expiry_date->diffInDays(now(), false);
    }

    /**
     * Get formatted expiry status.
     */
    public function getExpiryStatusAttribute(): string
    {
        if ($this->isExpired()) {
            return 'Expired ' . $this->expiry_date->diffForHumans();
        } elseif ($this->isNearExpiry()) {
            return 'Expires in ' . $this->expiry_date->diffForHumans();
        } else {
            return 'Expires in ' . $this->expiry_date->diffForHumans();
        }
    }
}
