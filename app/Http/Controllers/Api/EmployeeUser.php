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
        //
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
