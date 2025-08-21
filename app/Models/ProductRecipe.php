<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductRecipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'raw_material_id',
        'quantity_required',
    ];

    protected $casts = [
        'raw_material_id' => 'array',
        'quantity_required' => 'array',
    ];

    /**
     * Get the product that owns the recipe.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the raw materials for this recipe.
     */
    public function rawMaterials(): BelongsToMany
    {
        return $this->belongsToMany(RawMaterial::class, 'raw_material_id', 'id')
            ->using(function ($rawMaterialIds) {
                return RawMaterial::whereIn('id', $rawMaterialIds)->get();
            });
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
     * Get recipe ingredients with quantities.
     */
    public function getRecipeIngredientsAttribute(): array
    {
        $ingredients = [];
        
        if (is_array($this->raw_material_id) && is_array($this->quantity_required)) {
            $rawMaterials = RawMaterial::whereIn('id', $this->raw_material_id)->get()->keyBy('id');
            
            foreach ($this->raw_material_id as $index => $rawMaterialId) {
                if (isset($rawMaterials[$rawMaterialId])) {
                    $rawMaterial = $rawMaterials[$rawMaterialId];
                    $quantity = $this->quantity_required[$index] ?? 0;
                    
                    $ingredients[] = [
                        'raw_material' => $rawMaterial,
                        'quantity' => $quantity,
                        'unit' => $rawMaterial->unit->symbol ?? '',
                        'cost' => $rawMaterial->cost_per_unit * $quantity,
                    ];
                }
            }
        }
        
        return $ingredients;
    }

    /**
     * Get total recipe cost per unit.
     */
    public function getTotalCostAttribute(): float
    {
        $ingredients = $this->recipe_ingredients;
        $totalCost = 0;
        
        foreach ($ingredients as $ingredient) {
            $totalCost += $ingredient['cost'];
        }
        
        return round($totalCost, 2);
    }

    /**
     * Get recipe summary.
     */
    public function getRecipeSummaryAttribute(): string
    {
        $ingredients = $this->recipe_ingredients;
        $summary = [];
        
        foreach ($ingredients as $ingredient) {
            $summary[] = $ingredient['raw_material']->name . ' (' . $ingredient['quantity'] . ' ' . $ingredient['unit'] . ')';
        }
        
        return implode(', ', $summary);
    }

    /**
     * Check if recipe has all required ingredients.
     */
    public function hasAllIngredients(): bool
    {
        return !empty($this->raw_material_id) && !empty($this->quantity_required);
    }

    /**
     * Get ingredient count.
     */
    public function getIngredientCountAttribute(): int
    {
        return is_array($this->raw_material_id) ? count($this->raw_material_id) : 0;
    }
}
