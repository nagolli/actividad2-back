<?php

namespace Database\Seeders;

use App\Models\AppUser;
use App\Models\EmployeeUser;
use App\Models\EmployeeUserRole;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeUserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = AppUser::where('email', 'admin@example.com')->first();
        $adminEmployee = EmployeeUser::where('appUserId', $adminUser?->id)->first();
        $superadminRole = Role::where('name', 'Administrador')->first();
        
        if ($adminEmployee && $superadminRole) {
            EmployeeUserRole::create([
                'employeeUserId' => $adminEmployee->id,
                'roleId' => $superadminRole->id,
            ]);
        }
    }
}