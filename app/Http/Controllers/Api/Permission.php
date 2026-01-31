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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
