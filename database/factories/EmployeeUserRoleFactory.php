<?php

namespace Database\Factories;

use App\Models\EmployeeUser;
use App\Models\EmployeeUserRole;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeUserRole>
 */
class EmployeeUserRoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employeeUserId' => EmployeeUser::factory(),
            'roleId' => Role::factory(),
        ];
    }
}