<?php

namespace Database\Factories;

use App\Models\Route;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Store>
 */
class StoreFactory extends Factory
{
    protected $model = Store::class;

    public function definition(): array
    {
        return [
            'route_id' => Route::factory(),
            'name' => $this->faker->company(),
            'owner_name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'bill_to_bill_shop' => $this->faker->boolean(),
            'current_liabilities' => $this->faker->randomFloat(2, 0, 250000),
        ];
    }
}

