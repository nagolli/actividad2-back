<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\AppUserAddress;
use App\Models\AppUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppUserAddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = AppUser::where('email', 'admin@example.com')->first();
        $address = Address::where('street', 'Calle Falsa')->first();
        
        if ($adminUser && $address) {
            AppUserAddress::create([
                'addressId' => $address->id,
                'appUserId' => $adminUser->id,
                'name' => 'Dirección principal',
            ]);
        }
    }
}
