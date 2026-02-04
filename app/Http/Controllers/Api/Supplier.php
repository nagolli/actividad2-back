<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier as SupplierModel;

class Supplier extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $suppliers = SupplierModel::all();
            return response()->json($suppliers);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching suppliers'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:suppliers',
                'phone' => 'nullable|string|max:32',
                'email' => 'required|email|max:255|unique:suppliers',
                'inactive' => 'boolean',
            ]);

            $supplier = SupplierModel::create($validated);
            return response()->json($supplier, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating supplier'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $supplier = SupplierModel::findOrFail($id);
            return response()->json($supplier);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Supplier not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $supplier = SupplierModel::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:suppliers,name,' . $id,
                'phone' => 'nullable|string|max:32',
                'email' => 'required|email|max:255|unique:suppliers,email,' . $id,
                'inactive' => 'boolean',
            ]);

            $supplier->update($validated);
            return response()->json($supplier);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating supplier'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $supplier = SupplierModel::findOrFail($id);
            $supplier->delete();
            return response()->json(['message' => 'Supplier deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting supplier'], 500);
        }
    }
}
