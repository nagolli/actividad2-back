<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Order as OrderModel;
use App\Models\AppUser as AppUserModel;
use Illuminate\Support\Facades\Log;

class Order extends Controller
{
    /**
     * Filtrar pedidos
     * GET /api/orders/filter?state=pending&email=test@mail.com&productId=5&from=2026-01-01&to=2026-02-01
     */
    public function filter(Request $request)
    {
        try {
            $query = OrderModel::with(['user', 'address', 'products']);

            if ($request->filled('state')) {
                $query->where('state', $request->state);
            }

            if ($request->filled('email')) {
                $query->where('email', $request->email);
            }

            if ($request->filled('from')) {
                $query->whereDate('date', '>=', $request->from);
            }

            if ($request->filled('to')) {
                $query->whereDate('date', '<=', $request->to);
            }

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
     * Listado de pedidos
     */
    public function index()
    {
        try {
            $orders = OrderModel::with(['user', 'address', 'products'])->get();
            return response()->json($orders);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Crear un nuevo pedido
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'userId' => 'required|exists:appUsers,id',
                'products' => 'required|array|min:1',
                'products.*.id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
            ]);

            // Recuperar usuario y su primera dirección
            $user = AppUserModel::with('addresses')->findOrFail($validated['userId']);
            $address = $user->addresses()->first();

            if (!$address) {
                return response()->json(['error' => 'El usuario no tiene ninguna dirección asociada'], 422);
            }

            // Crear pedido
            $order = OrderModel::create([
                'date' => now(),
                'state' => 'pendiente',
                'userId' => $user->id,
                'addressId' => $address->id,
            ]);

            // Asociar productos
            $productIds = collect($validated['products'])->pluck('id');
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            $syncData = [];
            foreach ($validated['products'] as $item) {
                $product = $products->get($item['id']);
                if (!$product) {
                    Log::error("Producto no encontrado: " . $item['id']);
                    throw new \Exception("Producto no encontrado: " . $item['id']);
                }
                $syncData[$product->id] = [
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            }

            $order->products()->sync($syncData);

            return new OrderResource($order->load(['user','address','products']));

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error creando pedido: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json(['error' => 'Error creating order'], 500);
        }
    }

    public function byUser(string $userId)
    {
        try {
            $orders = OrderModel::with(['user', 'address', 'products'])
                ->where('userId', $userId)
                ->get();

            if ($orders->isEmpty()) {
                return response()->json([
                    'message' => 'No orders found for this user'
                ], 404);
            }

            return OrderResource::collection($orders);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error fetching user orders'
            ], 500);
        }
    }

    /**
     * Mostrar un pedido
     */
    public function show(string $id)
    {
        try {
            $order = OrderModel::with(['user', 'address', 'products'])->findOrFail($id);
            return new OrderResource($order);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Order not found'], 404);
        }
    }

    /**
     * Actualizar pedido
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

            $order->update($request->only(['state', 'addressId']));

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

            return new OrderResource($order->fresh(['user','address','products']));

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating order'], 500);
        }
    }

    /**
     * Eliminar pedido
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