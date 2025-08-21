<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class StockMovement extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($stockMovement) {
            if (!$stockMovement->created_by && Auth::check()) {
                $stockMovement->created_by = Auth::id();
            }
            
            if (!$stockMovement->movement_date) {
                $stockMovement->movement_date = now();
            }
        });
    }

    protected $fillable = [
        'movement_type',
        'product_id',
        'batch_number',
        'quantity',
        'notes',
        'created_by',
        'movement_date',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'movement_date' => 'datetime',
    ];

    /**
     * Get the product that owns the stock movement.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who created the stock movement.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
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
     * Check if this is a stock-in movement.
     */
    public function isStockIn(): bool
    {
        return in_array($this->movement_type, ['purchase', 'return', 'adjustment']);
    }

    /**
     * Check if this is a stock-out movement.
     */
    public function isStockOut(): bool
    {
        return in_array($this->movement_type, ['sale', 'transfer']);
    }

    /**
     * Get the movement type label.
     */
    public function getMovementTypeLabelAttribute(): string
    {
        return match($this->movement_type) {
            'purchase' => 'Purchase',
            'sale' => 'Sale',
            'transfer' => 'Transfer',
            'adjustment' => 'Adjustment',
            'return' => 'Return',
            default => 'Unknown',
        };
    }

    /**
     * Get the movement type color for display.
     */
    public function getMovementTypeColorAttribute(): string
    {
        return match($this->movement_type) {
            'purchase' => 'success', // Green for stock in
            'sale' => 'danger',      // Red for stock out
            'transfer' => 'info',    // Blue for transfers
            'adjustment' => 'warning', // Yellow for adjustments
            'return' => 'success',   // Green for returns (stock in)
            default => 'secondary',
        };
    }

    /**
     * Get the quantity with sign for display.
     */
    public function getQuantityWithSignAttribute(): string
    {
        $sign = $this->isStockIn() ? '+' : '-';
        return $sign . abs($this->quantity);
    }

    /**
     * Get the absolute quantity value.
     */
    public function getAbsoluteQuantityAttribute(): float
    {
        return abs($this->quantity);
    }

    /**
     * Check if movement has batch tracking.
     */
    public function hasBatchTracking(): bool
    {
        return !empty($this->batch_number);
    }

    /**
     * Get movement summary.
     */
    public function getMovementSummaryAttribute(): string
    {
        $summary = $this->movement_type_label . ' - ' . $this->quantity_with_sign;
        
        if ($this->hasBatchTracking()) {
            $summary .= ' (Batch: ' . $this->batch_number . ')';
        }
        
        if ($this->product) {
            $summary .= ' - ' . $this->product->name;
        }
        
        return $summary;
    }

    /**
     * Scope for stock-in movements.
     */
    public function scopeStockIn($query)
    {
        return $query->whereIn('movement_type', ['purchase', 'return', 'adjustment']);
    }

    /**
     * Scope for stock-out movements.
     */
    public function scopeStockOut($query)
    {
        return $query->whereIn('movement_type', ['sale', 'transfer']);
    }

    /**
     * Scope for specific movement type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('movement_type', $type);
    }

    /**
     * Scope for specific product.
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope for specific batch.
     */
    public function scopeForBatch($query, $batchNumber)
    {
        return $query->where('batch_number', $batchNumber);
    }
}
