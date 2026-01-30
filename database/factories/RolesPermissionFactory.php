<?php

namespace Database\Factories;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolesPermission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RolesPermission>
 */
class RolesPermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'roleId' => Role::factory(),
            'permissionId' => $this->faker->numberBetween(0, 5),
            'permissionLevel' => $this->faker->randomElement([0, 1, 2, 3]),
        ];
    }
}