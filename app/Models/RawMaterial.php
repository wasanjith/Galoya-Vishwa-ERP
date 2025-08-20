<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'unit_id',
        'cost_per_unit',
        'minimum_stock_level',
        'supplier_id',
        'is_active',
    ];

    protected $casts = [
        'cost_per_unit' => 'decimal:2',
        'minimum_stock_level' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the category that owns the raw material.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(RawMaterialCategory::class, 'category_id');
    }

    /**
     * Get the unit that owns the raw material.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    /**
     * Get the supplier that owns the raw material.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Get the stock information for this raw material.
     */
    public function stock(): HasOne
    {
        return $this->hasOne(RawMaterialStock::class);
    }

    /**
     * Get the purchases for this raw material.
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(RawMaterialPurchase::class);
    }
}
