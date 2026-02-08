<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Order as OrderModel;
class Order extends Controller
{

    // Probar con GET /api/orders/filter?state=pending&email=test@mail.com&productId=5&from=2026-01-01&to=2026-02-01
    public function filter(Request $request)
    {
        try {
            $query = OrderModel::with(['user', 'address', 'products']);

            // filtro por estado
            if ($request->filled('state')) {
                $query->where('state', $request->state);
            }

            // filtro por email
            if ($request->filled('email')) {
                $query->where('email', $request->email);
            }

            // filtro por rango de fechas
            if ($request->filled('from')) {
                $query->whereDate('date', '>=', $request->from);
            }

            if ($request->filled('to')) {
                $query->whereDate('date', '<=', $request->to);
            }

            // filtro por producto contenido en el pedido
            if ($request->filled('productId')) {
                $query->whereHas('products', function ($q) use ($request) {
                    $q->where('products.id', $request->productId);
                });
            }

            $orders = $query->get();

            return OrderResource::collection($orders);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error filtering orders'], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orders = OrderModel::with([
                'user',
                'address',
                'products'
            ])->get();

            return response()->json($orders);

        } catch (\Exception $e) {
            //return response()->json(['error' => 'Error fetching orders'], 500);
            return response()->json([
                'message' => $e->getMessage(),
            ], 500 );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         try {
            $validated = $request->validate([
                'email' => 'required|exists:users,email',
                'addressId' => 'required|exists:addresses,id',

                'products' => 'required|array|min:1',
                'products.*.id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
            ]);

            $order = OrderModel::create([
                'date' => now(),
                'state' => 'pendiente',
                'email' => $validated['email'],
                'addressId' => $validated['addressId'],
            ]);
            foreach ($validated['products'] as $item) {
                $product = Product::find($item['id']);

                $order->products()->attach($product->id, [
                    'quantity' => $item['quantity'],
                    'price' => $product->price
                ]);
            }

            return new OrderResource(
            $order->load(['user','address','products'])
        );

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
            $order = OrderModel::with(['user', 'address', 'products'])->findOrFail($id);
            return new OrderResource($order); // usar recurso simple
        } catch (\Exception $e) {
            return response()->json(['error' => 'Order not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'state' => 'sometimes|string',

                'addressId' => 'sometimes|exists:addresses,id',

                'products' => 'sometimes|array|min:1',
                'products.*.id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
            ]);

            $order = OrderModel::with('products')->findOrFail($id);

            // actualizar campos simples
            $order->update($request->only(['state', 'addressId']));

            // si vienen productos, se sincroniza el pivot
            if (isset($validated['products'])) {

                $syncData = [];

                foreach ($validated['products'] as $item) {
                    $product = Product::find($item['id']);

                    $syncData[$product->id] = [
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                    ];
                }

                $order->products()->sync($syncData);
            }

            return new OrderResource(
                $order->fresh(['user','address','products'])
            );

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating order'], 500);
        }
    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $order = OrderModel::findOrFail($id);
            $order->delete();

            return response()->json(['message' => 'Order deleted']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Order not found'], 404);
        }
    }
}
