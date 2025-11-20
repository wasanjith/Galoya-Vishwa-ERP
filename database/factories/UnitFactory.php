<?php

namespace Database\Factories;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    protected $model = Unit::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['kg', 'g', 'l', 'ml', 'pcs', 'm', 'cm']),
            'abbreviation' => $this->faker->randomElement(['kg', 'g', 'l', 'ml', 'pcs', 'm', 'cm']),
            'type' => $this->faker->randomElement(['weight', 'volume', 'length', 'count']),
            'is_active' => true,
        ];
    }
}