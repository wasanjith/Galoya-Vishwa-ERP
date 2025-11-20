<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreditSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'route_id',
        'store_id',
        'sale_date',
        'grand_total',
        'amount_paid',
        'credit_total',
        'notes',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'grand_total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'credit_total' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (CreditSale $sale): void {
            if (blank($sale->invoice_number)) {
                $sale->invoice_number = self::generateInvoiceNumber();
            }
        });

        static::saving(function (CreditSale $sale): void {
            $sale->credit_total = max(($sale->grand_total ?? 0) - ($sale->amount_paid ?? 0), 0);
        });
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CreditSaleItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(CreditSalePayment::class);
    }

    protected static function generateInvoiceNumber(): string
    {
        $prefix = 'INV-';
        $latestId = (int) (self::max('id') ?? 0) + 1;

        return $prefix . str_pad((string) $latestId, 6, '0', STR_PAD_LEFT);
    }
}

