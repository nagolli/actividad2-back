<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Administrador']);
        Role::create(['name' => 'Por determinar']);
        Role::create(['name' => 'Recursos Humanos']);
        Role::create(['name' => 'Encargado de Tienda']);
        Role::create(['name' => 'Encargado de Almacén']);
        Role::create(['name' => 'Social Media Manager']);
        Role::create(['name' => 'Ventas']);
    }
}
