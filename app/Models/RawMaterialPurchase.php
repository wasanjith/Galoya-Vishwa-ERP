<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RawMaterialPurchase extends Model
{
    protected $fillable = [
        'raw_material_id',
        'supplier_id',
        'invoice_number',
        'quantity',
        'total_amount',
        'payment_status',
        'amount_paid',
        'balance_amount',
        'invoice_soft_copy',
        'invoice_file_type',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_amount' => 'decimal:2',
    ];

    protected $appends = [
        'remaining_balance',
        'payment_percentage',
        'payment_status_color',
        'invoice_url',
    ];

    /**
     * Get the raw material that was purchased.
     */
    public function rawMaterial(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id');
    }

    /**
     * Get the supplier for this purchase.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Get the raw material stock for this purchase's raw material.
     */
    public function rawMaterialStock()
    {
        return $this->hasOneThrough(
            RawMaterialStock::class,
            RawMaterial::class,
            'id', // Foreign key on RawMaterial table
            'raw_material_id', // Foreign key on RawMaterialStock table
            'raw_material_id', // Local key on RawMaterialPurchase table
            'id' // Local key on RawMaterial table
        );
    }

    /**
     * Check if the purchase is fully paid.
     */
    public function isFullyPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if the purchase is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->payment_status === 'overdue';
    }

    /**
     * Check if the purchase has partial payment.
     */
    public function hasPartialPayment(): bool
    {
        return $this->payment_status === 'partial';
    }

    /**
     * Get the remaining balance amount.
     */
    public function getRemainingBalanceAttribute(): float
    {
        return $this->total_amount - $this->amount_paid;
    }

    /**
     * Get the payment percentage.
     */
    public function getPaymentPercentageAttribute(): float
    {
        if ($this->total_amount == 0) {
            return 0;
        }
        
        return ($this->amount_paid / $this->total_amount) * 100;
    }

    /**
     * Get the payment status color for UI display.
     */
    public function getPaymentStatusColorAttribute(): string
    {
        return match($this->payment_status) {
            'pending' => 'warning',
            'partial' => 'info',
            'paid' => 'success',
            'overdue' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get the invoice URL for display.
     */
    public function getInvoiceUrlAttribute(): string
    {
        return $this->invoice_soft_copy ? asset('storage/' . $this->invoice_soft_copy) : '';
    }

    /**
     * Get the invoice file path.
     */
    public function getInvoicePathAttribute(): string
    {
        return $this->invoice_soft_copy ? storage_path('app/public/' . $this->invoice_soft_copy) : '';
    }

    /**
     * Check if invoice file exists.
     */
    public function hasInvoice(): bool
    {
        return !empty($this->invoice_soft_copy);
    }

    /**
     * Scope to get purchases by payment status.
     */
    public function scopeByPaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    /**
     * Scope to get pending purchases.
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope to get overdue purchases.
     */
    public function scopeOverdue($query)
    {
        return $query->where('payment_status', 'overdue');
    }

    /**
     * Scope to get paid purchases.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope to get purchases with partial payment.
     */
    public function scopePartial($query)
    {
        return $query->where('payment_status', 'partial');
    }

    /**
     * Scope to get purchases with outstanding balance.
     */
    public function scopeWithBalance($query)
    {
        return $query->where('balance_amount', '>', 0);
    }

    /**
     * Scope to get purchases by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope to get purchases by amount range.
     */
    public function scopeByAmountRange($query, $minAmount, $maxAmount)
    {
        return $query->whereBetween('total_amount', [$minAmount, $maxAmount]);
    }

    /**
     * Boot method to automatically calculate balance amount.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($purchase) {
            // Auto-calculate balance amount if not set
            if (!isset($purchase->balance_amount) || $purchase->balance_amount === null) {
                $purchase->balance_amount = $purchase->total_amount - $purchase->amount_paid;
            }

            // Auto-update payment status based on amounts
            if ($purchase->amount_paid >= $purchase->total_amount) {
                $purchase->payment_status = 'paid';
            } elseif ($purchase->amount_paid > 0) {
                $purchase->payment_status = 'partial';
            } else {
                $purchase->payment_status = 'pending';
            }

            // Auto-detect file type if invoice is uploaded
            if ($purchase->invoice_soft_copy && !$purchase->invoice_file_type) {
                $extension = pathinfo($purchase->invoice_soft_copy, PATHINFO_EXTENSION);
                $purchase->invoice_file_type = strtoupper($extension);
            }
            
            // Ensure balance amount is calculated
            $purchase->balance_amount = $purchase->total_amount - $purchase->amount_paid;
        });
    }
}
