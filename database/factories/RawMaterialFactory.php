<?php

namespace Database\Factories;

use App\Models\RawMaterial;
use App\Models\RawMaterialCategory;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class RawMaterialFactory extends Factory
{
    protected $model = RawMaterial::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'raw_material_category_id' => RawMaterialCategory::factory(),
            'unit_id' => Unit::factory(),
            'minimum_stock_level' => $this->faker->randomFloat(2, 10, 100),
            'maximum_stock_level' => $this->faker->randomFloat(2, 200, 500),
            'reorder_point' => $this->faker->randomFloat(2, 50, 150),
            'cost_per_unit' => $this->faker->randomFloat(2, 1, 50),
            'is_active' => true,
        ];
    }
}