<?php

namespace Database\Seeders;

use App\Models\AppUser;
use App\Models\EmployeeUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = AppUser::where('email', 'admin@example.com')->first();
        
        if ($adminUser) {
            EmployeeUser::create([
                'appUserId' => $adminUser->id,
                'password' => Hash::make('admin'),
                'isInactive' => false,
            ]);
        }
    }
}