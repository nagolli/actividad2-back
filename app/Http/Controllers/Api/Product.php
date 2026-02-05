<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product as ProductModel;

class Product extends Controller
{
    /**
     * Get minimum and maximum price from products.
     */
    public function priceRange()
    {
        try {
            $minPrice = (float) (ProductModel::min('price') ?? 0);
            $maxPrice = (float) (ProductModel::max('price') ?? 0);

            return response()->json([
                'min' => $minPrice,
                'max' => $maxPrice,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching price range'], 500);
        }
    }

    /**
     * Filter products by multiple criteria.
     */
    public function filter(Request $request)
    {
        try {
            $query = ProductModel::query();

            // Filter by name (partial match)
            if ($request->has('name') && $request->name) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            // Filter by category
            if ($request->has('category') && $request->category) {
                $query->where('categoryId', $request->category);
            }

            // Filter by supplier
            if ($request->has('supplier') && $request->supplier) {
                $query->where('supplierId', $request->supplier);
            }

            // Filter by minimum price
            if ($request->has('min') && is_numeric($request->min)) {
                $query->where('price', '>=', (float) $request->min);
            }

            // Filter by maximum price
            if ($request->has('max') && is_numeric($request->max)) {
                $query->where('price', '<=', (float) $request->max);
            }

            $products = $query->get();
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error filtering products'], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = ProductModel::all();
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching products'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:products',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|string',
                'inactive' => 'boolean',
                'categoryId' => 'required|exists:categories,id',
                'supplierId' => 'required|exists:suppliers,id',
            ]);

            $product = ProductModel::create($validated);
            return response()->json($product, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating product'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = ProductModel::findOrFail($id);
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $product = ProductModel::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:products,name,' . $id,
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|string',
                'inactive' => 'boolean',
                'categoryId' => 'required|exists:categories,id',
                'supplierId' => 'required|exists:suppliers,id',
            ]);

            $product->update($validated);
            return response()->json($product);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating product'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = ProductModel::findOrFail($id);
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting product'], 500);
        }
    }
}
