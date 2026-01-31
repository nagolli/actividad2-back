<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeUser as EmployeeUserModel;
use App\Http\Resources\EmployeeUserResource;
use App\Http\Resources\EmployeeUserListResource;


class EmployeeUser extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $employees = EmployeeUserModel::with('appUser')->get();
            return EmployeeUserListResource::collection($employees);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching employees'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email|unique:appUsers|max:64',
                'name' => 'required|string|max:64',
                'surname' => 'required|string|max:128',
                'phone' => 'required|string|max:32',
                'password' => 'required|string|min:8',
                'isInactive' => 'boolean',
                'addresses' => 'nullable|array',
                'addresses.*.addressId' => 'required|exists:addresses,id',
                'addresses.*.name' => 'required|string|max:128',
                'roles' => 'nullable|array',
                'roles.*.roleId' => 'required|exists:roles,id',
            ]);

            DB::transaction(function () use ($validated, $employee) {

            //Primero crear el appUser al que va a vincular el employeeUser
            $guest = AppUserModel::create($validated);

            if (isset($validated['addresses'])) {
                foreach ($validated['addresses'] as $address) {
                    $guest->addresses()->attach($address['addressId'], ['name' => $address['name']]);
                }
            }
            //Luego inicilaizar el employeeUser vinculado al appUser creado
            $employee = EmployeeUserModel::create(['appUserId' => $guest->id]);

            if (isset($validated['roles'])) {
                foreach ($validated['roles'] as $role) {
                    $employee->role()->attach($role['roleId']);
                }
            }
            return new EmployeeUserResource($employee->load('appUser.addresses','role'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating employee'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $employee = EmployeeUserModel::with('appUser.addresses','role.permission')->findOrFail($id);
            return new EmployeeUserResource($employee); // usar recurso simple
        } catch (\Exception $e) {
            return response()->json(['error' => 'Employee not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $employee = EmployeeUserModel::with('appUser')->findOrFail($id);

            $validated = $request->validate([
                'email' => 'sometimes|email|unique:app_users,email,' . $employee->appUserId,
                'name' => 'sometimes|string|max:64',
                'surname' => 'sometimes|string|max:128',
                'phone' => 'sometimes|string|max:32',
                'password' => 'sometimes|string|min:8',
                'isInactive' => 'sometimes|boolean',
                'addresses' => 'sometimes|array',
                'addresses.*.addressId' => 'required|exists:addresses,id',
                'addresses.*.name' => 'required|string|max:128',
                'roles' => 'sometimes|array',
                'roles.*.roleId' => 'required|exists:roles,id',
            ]);

            $employee->appUser->update(
                collect($validated)->only([
                    'email', 'name', 'surname', 'phone'
                ])->toArray()
            );

            if (array_key_exists('addresses', $validated)) {
                $syncAddresses = [];

                foreach ($validated['addresses'] as $address) {
                    $syncAddresses[$address['addressId']] = [
                        'name' => $address['name']
                    ];
                }

                $employee->appUser->addresses()->sync($syncAddresses);
            }

            $employee->update(
                collect($validated)->only([
                    'password', 'isInactive'
                ])->toArray()
            );

            if (array_key_exists('roles', $validated)) {
                $employee->role()->sync(
                    collect($validated['roles'])->pluck('roleId')
                );
            }
            return new EmployeeUserResource($employee->load('appUser.addresses','role'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating employee'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $employee = EmployeeUserModel::findOrFail($id);

            //Caso 1, si el empleado es el único que tiene permiso 0 a nivel 3, no se puede borrar
            $criticalPermissions = $employee->role->permission()
                ->where('permissionLevel', '>=', 3)
                ->pluck('permissions.id')
                ->toArray();
            
            foreach ($criticalPermissions as $permissionId) {
                $count = EmployeeUserModel::whereHas('role.permission', function ($query) use
                ($permissionId) {
                    $query->where('permissions.id', $permissionId)
                          ->where('permissionLevel', '>=', 3);
                })->count();
                if ($count <= 1) {
                    return response()->json(['error' => 'Cannot delete employee with critical permissions'], 403);
                }
            }

            //Caso 1, el empleado no es tambien cliente -> Borra en cadena appUser y desvincula direccion
            if (!$employee->appUser->clientUser) {
                $employee->appUser()->addresses()->detach();
                $employee->appUser()->delete();
            }
            //Caso 2, el cliente es tambien empleado, solo elimina este empleado
            //Desvincular roles y borrar empleado
            $employee->role()->detach();
            $employee->delete();
            return response()->json(['message' => 'Employee deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting employee'], 500);
        }
    }
}
