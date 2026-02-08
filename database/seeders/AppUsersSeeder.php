<?php

namespace Database\Seeders;

use App\Models\AppUser;
use App\Models\ClientUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppUser::create([
            'email' => 'admin@example.com',
            'name' => 'admin',
            'surname' => '',
            'phone' => null,
        ]);

    }
}