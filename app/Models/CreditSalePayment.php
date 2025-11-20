<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditSalePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'credit_sale_id',
        'store_id',
        'paid_date',
        'amount',
        'reference',
        'notes',
    ];

    protected $casts = [
        'paid_date' => 'date',
        'amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (CreditSalePayment $payment): void {
            if (! $payment->store_id && $payment->creditSale) {
                $payment->store_id = $payment->creditSale->store_id;
            }
        });

        static::created(function (CreditSalePayment $payment): void {
            $payment->applyAmountChange((float) $payment->amount);
        });

        static::updated(function (CreditSalePayment $payment): void {
            $originalAmount = (float) $payment->getOriginal('amount');
            $currentAmount = (float) $payment->amount;
            $difference = $currentAmount - $originalAmount;

            if ($difference !== 0.0) {
                $payment->applyAmountChange($difference);
            }
        });

        static::deleted(function (CreditSalePayment $payment): void {
            $payment->applyAmountChange(-1 * (float) $payment->amount);
        });
    }

    protected function applyAmountChange(float $difference): void
    {
        if ($difference === 0.0) {
            return;
        }

        $sale = $this->creditSale;

        if (! $sale) {
            return;
        }

        $sale->amount_paid = max(($sale->amount_paid ?? 0) + $difference, 0);
        $sale->save();
    }

    public function creditSale(): BelongsTo
    {
        return $this->belongsTo(CreditSale::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}

