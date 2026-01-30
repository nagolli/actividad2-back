<?php

namespace Database\Seeders;

use App\Models\AppUser;
use App\Models\ClientUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = AppUser::where('email', 'admin@example.com')->first();
        
        if ($adminUser) {
            ClientUser::create([
                'appUserId' => $adminUser->id,
                'password' => Hash::make('client'),
                'level' => 0,
                'points' => 0,
            ]);
        }
    }
}