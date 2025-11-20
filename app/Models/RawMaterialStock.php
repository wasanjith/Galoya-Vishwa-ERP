<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RawMaterialStock extends Model
{
    protected $table = 'raw_material_stock';

    protected $fillable = [
        'raw_material_id',
        'quantity_available',
        'last_updated',
    ];

    protected $casts = [
        'quantity_available' => 'decimal:3',
        'last_updated' => 'datetime',
    ];

    /**
     * Get the raw material that owns the stock.
     */
    public function rawMaterial(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class);
    }

    /**
     * Add quantity to the available stock.
     */
    public function addStock(float $quantity): void
    {
        $this->quantity_available += $quantity;
        $this->last_updated = now();
        $this->save();
    }

    /**
     * Remove quantity from the available stock.
     */
    public function removeStock(float $quantity): void
    {
        $this->quantity_available = max(0, $this->quantity_available - $quantity);
        $this->last_updated = now();
        $this->save();
    }

    /**
     * Get or create a stock record for the given raw material.
     */
    public static function getOrCreateForRawMaterial(int $rawMaterialId): self
    {
        return static::firstOrCreate(
            ['raw_material_id' => $rawMaterialId],
            [
                'quantity_available' => 0,
                'last_updated' => now()
            ]
        );
    }
}
