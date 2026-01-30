<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\AppUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppUserAddresses>
 */
class AppUserAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'addressId' => Address::factory(),
            'appUserId' => AppUser::factory(),
            'name' => $this->faker->optional()->words(2, true),
        ];
    }
}