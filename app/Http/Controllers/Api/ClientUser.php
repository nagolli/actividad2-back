<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientUser as ClientUserModel;
use App\Http\Resources\ClientUserResource;
use App\Http\Resources\ClientUserListResource;

class ClientUser extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $clients = ClientUserModel::with('appUser')->get();
            return ClientUserListResource::collection($clients);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching clients'], 500);
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
            $client = ClientUserModel::with('appUser.addresses')->findOrFail($id);
            return new ClientUserResource($client);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Client not found'], 404);
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
