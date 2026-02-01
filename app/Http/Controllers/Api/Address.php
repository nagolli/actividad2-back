<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address as AddressModel;
use App\Http\Resources\AddressResource;

class Address extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $addresses = AddressModel::all();
            return AddressResource::collection($addresses);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching addresses'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'street' => 'required|string|max:128',
                'number' => 'required|string|max:10',
                'city' => 'required|string|max:64',
                'province' => 'required|string|max:64',
                'postalCode' => 'required|string|max:8',
                'country' => 'required|string|max:64',
                'floor' => 'nullable|string|max:10',
                'door' => 'nullable|string|max:10',
                'staircase' => 'nullable|string|max:10',
            ]);

            $address = AddressModel::create($validated);
            return new AddressResource($address);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating address'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $address = AddressModel::findOrFail($id);
            return new AddressResource($address);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Address not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $address = AddressModel::findOrFail($id);

            $validated = $request->validate([
                'street' => 'sometimes|string|max:128',
                'number' => 'sometimes|string|max:10',
                'city' => 'sometimes|string|max:64',
                'province' => 'sometimes|string|max:64',
                'postalCode' => 'sometimes|string|max:8',
                'country' => 'sometimes|string|max:64',
                'floor' => 'sometimes|string|max:10',
                'door' => 'sometimes|string|max:10',
                'staircase' => 'sometimes|string|max:10',
            ]);

            $address->update($validated);
            return new AddressResource($address);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Address not found'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating address'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $address = AddressModel::findOrFail($id);
            $address->appUsers()->detach();
            $address->delete();
            return response()->json(['message' => 'Address deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting address'], 500);
        }
    }
}
