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
        //
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
