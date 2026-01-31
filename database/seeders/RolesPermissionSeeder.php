<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolesPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Superadmin - acceso total (nivel 3)
        Role::where('name', 'Administrador')->first()->permission()->attach([
            0 => ['permissionLevel' => 3]
        ]);

        // Recursos Humanos - gestión avanzada de roles (3), consulta permisos (1)
        Role::where('name', 'Recursos Humanos')->first()->permission()->attach([
            1 => ['permissionLevel' => 3],
        ]);

        // Encargado de Tienda - edición productos (2), consulta existencias (1), consulta reservas (1)
        Role::where('name', 'Encargado de Tienda')->first()->permission()->attach([
            2 => ['permissionLevel' => 2],
            3 => ['permissionLevel' => 1],
            4 => ['permissionLevel' => 1],
        ]);

        // Encargado de Almacén - edición existencias (2), consulta productos (1)
        Role::where('name', 'Encargado de Almacén')->first()->permission()->attach([
            2 => ['permissionLevel' => 1],
            3 => ['permissionLevel' => 2],
        ]);

        // Social Media Manager - edición promociones (2) y permisos para gestionar eventos y reservas
        Role::where('name', 'Social Media Manager')->first()->permission()->attach([
            5 => ['permissionLevel' => 2],
            4 => ['permissionLevel' => 3]
        ]);

        // Ventas - edición avanzada de productos (3), consulta promociones (1)
        Role::where('name', 'Ventas')->first()->permission()->attach([
            2 => ['permissionLevel' => 3],
            5 => ['permissionLevel' => 1],
        ]);
    }
}