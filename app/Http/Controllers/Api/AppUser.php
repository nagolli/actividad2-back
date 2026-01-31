<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppUser as AppUserModel;
use App\Models\Address as AddressModel;

class AppUser extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $guests = AppUserModel::whereDoesntHave('clientUser')
                ->whereDoesntHave('employeeUser')
                ->get();
            return ClientUserListResource::collection($guests);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching guests'], 500);
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
            $guest = AppUserModel::whereDoesntHave('clientUser')
                ->whereDoesntHave('employeeUser')
                ->with('addresses')
                ->findOrFail($id);
            return new ClientUserResource($guest);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Guest not found'], 404);
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
