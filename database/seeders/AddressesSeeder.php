<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Address::create([
            'country' => 'España',
            'province' => 'Madrid',
            'city' => 'Madrid',
            'street' => 'Calle Falsa',
            'number' => 123,
            'postalCode' => '28001',
            'floor' => null,
            'door' => null,
            'staircase' => null,
        ]);
    }
}
