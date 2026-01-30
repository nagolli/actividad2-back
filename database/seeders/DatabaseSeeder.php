<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AppUsersSeeder::class,
            AddressesSeeder::class,
            AppUserAddressesSeeder::class,
            ClientUsersSeeder::class,
            EmployeeUsersSeeder::class,
            RolesSeeder::class,
            PermissionsSeeder::class,
            RolesPermissionSeeder::class,
            EmployeeUserRolesSeeder::class,
        ]);
    }
}
