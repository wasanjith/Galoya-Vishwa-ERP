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
}
