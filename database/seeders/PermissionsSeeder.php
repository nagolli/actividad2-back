<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create([
            'id' => 0,
            'description' => 'Permiso de administrador'
        ]);
        Permission::create([
            'id' => 1,
            'description' => 'Gestión de roles y privilegios'
        ]);
        Permission::create([
            'id' => 2,
            'description' => 'Gestión de productos'
        ]);
        Permission::create([
            'id' => 3,
            'description' => 'Gestión de existencias'
        ]);
        Permission::create([
            'id' => 4,
            'description' => 'Gestión de reservas'
        ]);
        Permission::create([
            'id' => 5,
            'description' => 'Promociones'
        ]);
        Permission::create([
            'id' => 6,
            'description' => 'Gestión de usuarios'
        ]);
    }
}
