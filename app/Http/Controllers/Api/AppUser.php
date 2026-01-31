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
        try {
            $validated = $request->validate([
                'email' => 'required|email|unique:appUsers|max:64',
                'name' => 'required|string|max:64',
                'surname' => 'required|string|max:128',
                'phone' => 'required|string|max:32',
                'addresses' => 'nullable|array',
                'addresses.*.addressId' => 'required|exists:addresses,id',
                'addresses.*.name' => 'required|string|max:128'
            ]);

            $guest = AppUserModel::create($validated);

            if (isset($validated['addresses'])) {
                foreach ($validated['addresses'] as $address) {
                    $guest->addresses()->attach($address['addressId'], ['name' => $address['name']]);
                }
            }

            return new AppUserResource($guest->load('addresses'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating guest'], 500);
        }
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
        try {
            $guest = AppUserModel::whereDoesntHave('clientUser')
                ->whereDoesntHave('employeeUser')
                ->findOrFail($id);

            $validated = $request->validate([
                'email' => 'sometimes|email|max:64|unique:appUsers,email,' . $id,
                'name' => 'sometimes|string|max:64',
                'surname' => 'sometimes|string|max:128',
                'phone' => 'sometimes|string|max:32',
                'addresses' => 'nullable|array',
                'addresses.*.addressId' => 'required|exists:addresses,id',
                'addresses.*.name' => 'required|string|max:128'
            ]);

            $guest->update($validated);

            if (isset($validated['addresses'])) {
                $guest->addresses()->detach();
                foreach ($validated['addresses'] as $address) {
                    $guest->addresses()->attach($address['addressId'], ['name' => $address['name']]);
                }
            }

            return new AppUserResource($guest->load('addresses'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating guest'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $guest = AppUserModel::whereDoesntHave('clientUser')
                ->whereDoesntHave('employeeUser')
                ->findOrFail($id);

            $guest->addresses()->detach();
            $guest->delete();

            return response()->json(['message' => 'Guest deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting guest'], 500);
        }
    }
}
