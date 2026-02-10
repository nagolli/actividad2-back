<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use \App\Models\AppUser;
use \App\Models\ClientUser;
use \App\Models\EmployeeUser;
use \App\Http\Controllers\Api\ClientUser as ClientUserController;
use \App\Http\Controllers\Api\EmployeeUser as EmployeeUserController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use \App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Cache;


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
            $appUser = AppUser::where('email', $credentials['email'])->first();
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

    // Mapa estático: token => [email, password, expires_at]
    private static array $tokenPasswordEmail = [];

    public function forgottenPassword(Request $request)
    {
        try {
            $target = $request->validate([
                'email' => 'required|email'
            ]);
            //0.Verificar que el email esta en uso
            $c = ClientUser::findUserByEmail($target['email']);
            $e = EmployeeUser::findUserByEmail($target['email']);
            if (empty($e) && empty($c)) {
                //Mismo mensaje que si existiera para no aportar información de no existencia de correos
                return response()->json(['success' => 'Sended email'], 200);
            }

            // 1. Generar token aleatorio
            $token = bin2hex(random_bytes(32));

            // 2. Generar contraseña aleatoria
            $newPassword = Str::random(12);

            // 3. Guardar en el mapa por 15 minutos
            Cache::put(
                "password_reset:$token",
                [
                    'email' => $target['email'],
                    'password' => $newPassword,
                ],
                now()->addMinutes(15)
            );

            // 4. Enviar correo notificando
            Mail::to($target['email'])->send(new PasswordResetMail($token, $newPassword));

            return response()->json(['success' => 'Sended email'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Forgotten password failed' . $e->getMessage()], 500);
        }
    }

    public function resetPassword(string $token)
    {
        try {
            // 1. Verificar que el token exista
            $data = Cache::get("password_reset:$token");
            if (!$data) {
                return response()->json(['error' => 'Token not found'], 400);
            }

            // 2. Obtener email y contraseña generada
            $c = ClientUser::findUserByEmail($data['email']);
            $e = EmployeeUser::findUserByEmail($data['email']);
            $newPassword = $data['password'];

            // 3. Eliminar del listado
            Cache::forget("password_reset:$token");

            // 4. Setear la contraseña del usuario
            if ($c) {
                ClientUserController::updatePassword($newPassword, $c->id);
            }
            if ($e) {
                EmployeeUserController::updatePassword($newPassword, $e->id);
            }

            return response()->json(['success' => 'Changed password'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Unexpected error' . $e->getMessage()], 500);
        }
    }
}

