<?php

namespace Database\Factories;

use App\Models\RawMaterialCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class RawMaterialCategoryFactory extends Factory
{
    protected $model = RawMaterialCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
}