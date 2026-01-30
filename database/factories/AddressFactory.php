<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'postalCode' => $this->faker->postcode(),
            'floor' => $this->faker->optional()->numberBetween(0, 10),
            'door' => $this->faker->optional()->numberBetween(1, 5),
            'staircase' => $this->faker->optional()->numberBetween(1, 5),
            'country' => $this->faker->country(),
            'province' => $this->faker->state(),
            'city' => $this->faker->city(),
            'street' => $this->faker->streetName(),
            'number' => $this->faker->numberBetween(1, 999),
        ];
    }
}
