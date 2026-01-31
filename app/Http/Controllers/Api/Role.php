<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role as RoleModel;
use App\Http\Resources\RoleResource;
use App\Http\Resources\RoleListResource;

class Role extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $roles = RoleModel::all();
            return RoleListResource::collection($roles);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching roles'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|unique:roles',
                'permissions' => 'nullable|array',
                'permissions.*.permissionId' => 'required|exists:permissions,id',
                'permissions.*.permissionLevel' => 'required|in:0,1,2,3'
            ]);

            $role = RoleModel::create(['name' => $validated['name']]);

            if (isset($validated['permissions'])) {
                foreach ($validated['permissions'] as $permission) {
                    $role->permission()->attach(
                        $permission['permissionId'],
                        ['permissionLevel' => $permission['permissionLevel']]
                    );
                }
            }

            return new RoleResource($role->load('permission'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating role'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $role = RoleModel::with('permission')->findOrFail($id);
            return new RoleResource($role);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Role not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $role = RoleModel::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|string|unique:roles,name,' . $id,
                'permissions' => 'nullable|array',
                'permissions.*.permissionId' => 'required|exists:permissions,id',
                'permissions.*.permissionLevel' => 'required|in:0,1,2,3'
            ]);

            $role->update($validated);

            if (isset($validated['permissions'])) {
                $role->permission()->detach();
                foreach ($validated['permissions'] as $permission) {
                    $role->permission()->attach(
                        $permission['permissionId'],
                        ['permissionLevel' => $permission['permissionLevel']]
                    );
                }
            }

            return new RoleResource($role->load('permission'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating role'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $role = RoleModel::findOrFail($id);
            $role->permission()->detach();
            $role->delete();
            return response()->json(['message' => 'Role deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting role'], 500);
        }
    }
}
