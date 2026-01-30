<?php

namespace Database\Factories;

use App\Models\AppUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientUsers>
 */
class ClientUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'appUserId' => AppUser::factory(),
            'password' => Hash::make('password123'),
            'level' => $this->faker->numberBetween(0, 3),
            'points' => $this->faker->numberBetween(0, 1000),
        ];
    }
}