<?php

namespace Database\Factories;

use App\Models\AppUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\Role as RoleModel;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeUser>
 */
class EmployeeUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'appUserId' => AppUser::factory()->withAddresses()->create()->id,
            'password' => Hash::make('empleado'),
            'isInactive' => $this->faker->boolean(20), // 20% probabilidad de estar inactivo
        ];
    }

    // Asignar roles aleatorios
    public function withRandomRoles(int $count = 1)
    {
        return $this->afterCreating(function ($employee) use ($count) {
            $roles = RoleModel::inRandomOrder()->take($count)->pluck('id');
            $employee->role()->attach($roles);
        });
    }
}
