<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Address;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppUsers>
 */
class AppUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'phone' => $this->faker->phoneNumber(),
        ];
    }

    public function withAddresses(int $count = 1)
    {
        return $this->afterCreating(function ($appUser) use ($count) {

            // Crear N direcciones
            $addresses = Address::factory()->count($count)->create();

            // Adjuntar al pivot con campo "name"
            $pivotData = $addresses->pluck('id')->mapWithKeys(function ($id) {
                return [
                    $id => ['name' => 'Principal']
                ];
            });

            $appUser->addresses()->attach($pivotData);
        });
    }
}
