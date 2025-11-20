<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'contact_person' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'payment_terms' => $this->faker->randomElement(['Net 30', 'Net 60', 'COD', 'Prepaid']),
            'tax_number' => $this->faker->numerify('TAX###########'),
            'bank_details' => $this->faker->text(200),
            'is_active' => true,
        ];
    }
}