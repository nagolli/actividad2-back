<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\AppUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'date' => now(),
            'state' => $this->faker->randomElement(['pendiente', 'enviado']),
            'userId' => AppUser::inRandomOrder()->value('id'),
            'addressId' => Address::inRandomOrder()->value('id'),
        ];
    }
}
