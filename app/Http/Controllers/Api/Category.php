<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category as CategoryModel;

class Category extends Controller
{
    /**
     * Display a listing of the resource in label/value format.
     */
    public function options()
    {
        try {
            $categories = CategoryModel::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->get()
                ->map(function ($category) {
                    return [
                        'label' => $category->name,
                        'value' => (string) $category->id,
                    ];
                });

            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching category options'], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = CategoryModel::all();
            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching categories'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories',
            ]);

            $category = CategoryModel::create($validated);
            return response()->json($category, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating category'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $category = CategoryModel::findOrFail($id);
            return response()->json($category);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $category = CategoryModel::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $id,
            ]);

            $category->update($validated);
            return response()->json($category);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating category'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = CategoryModel::findOrFail($id);
            $category->delete();
            return response()->json(['message' => 'Category deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting category'], 500);
        }
    }
}
