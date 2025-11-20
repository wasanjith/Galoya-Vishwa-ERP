<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'name',
        'owner_name',
        'phone',
        'address',
        'bill_to_bill_shop',
        'current_liabilities',
    ];

    protected $casts = [
        'bill_to_bill_shop' => 'boolean',
        'current_liabilities' => 'decimal:2',
    ];

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function creditSales(): HasMany
    {
        return $this->hasMany(CreditSale::class);
    }

    public function creditSalePayments(): HasMany
    {
        return $this->hasMany(CreditSalePayment::class);
    }
}

