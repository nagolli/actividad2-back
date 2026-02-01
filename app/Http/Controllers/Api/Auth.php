<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class Auth extends Controller
{

    //Funcion login, a partir de un correo y una contraseña, devuelve un json que contiene:
    // A - El usuario es un cliente: True/False
    // B - Si el usuario es un empleado: Array de pares permiso-nivel
    public function login()
    {
        try {
            $credentials = request()->validate([
                'email' => 'required|email',
                'password' => 'required|string'
            ]);

            //Hay que obtener el AppUser con el correo indicado
            $appUser = \App\Models\AppUser::where('email', $credentials['email'])->first();
            $isClient = false;
            $isEmployee = false;

            if (!$appUser) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
            //Si tenemos appUser, del ClientUser asociado ver si coincide la contraseña.
            $clientUser = $appUser->clientUser;

            if ($clientUser && Hash::check($credentials['password'], $clientUser->password)) {
                $isClient = true;
            }

            //Si tenemos appUser, del EmployeeUser asociado ver si coincide la contraseña, en ese caso sí, esta autenticado
            $employeeUser = $appUser->employeeUser;
            if ($employeeUser && Hash::check($credentials['password'], $employeeUser->password)) {
                $isEmployee = !$employeeUser->isInactive;
            }

            if (!($isClient || $isEmployee)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            $response = [
                'appClientId' => $appUser->id,
                'is_client' => $isClient,
                'is_employee' => $isEmployee,
                'employee_permissions' => $isEmployee
                    ? $employeeUser->load('role.permission')->role
                        ->flatMap(function ($role) {
                            return $role->permission->map(function ($permission) {
                                return [
                                    'id' => $permission->id,
                                    'lvl' => $permission->pivot->permissionLevel,
                                ];
                            });
                        })->unique()->values() : []
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Login failed'], 500);
        }
    }
}