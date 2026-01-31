<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission as PermissionModel;
use App\Http\Resources\PermissionResource;

class Permission extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $permissions = PermissionModel::all();
            return PermissionResource::collection($permissions);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching permissions'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $permission = PermissionModel::findOrFail($id);
            return new PermissionResource($permission);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Permission not found'], 404);
        }
    }
}
