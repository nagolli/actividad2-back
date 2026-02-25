<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order as OrderModel;
use App\Models\Product;
use App\Models\Address as AddressModel;
use App\Models\AppUser as AppUserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FastOrder extends Controller
{
    public function store(Request $request)
    {
        try {

            // 
            $validated = $request->validate([
                // Usuario
                'email' => 'required|email|max:64',
                'name' => 'required|string|max:64',
                'surname' => 'required|string|max:128',
                'phone' => 'nullable|string|max:32',

                // Dirección
                'street' => 'required|string|max:128',
                'number' => 'nullable|string|max:10',
                'city' => 'required|string|max:64',
                'province' => 'required|string|max:64',
                'postalCode' => 'required|string|max:8',
                'country' => 'required|string|max:64',
                'floor' => 'nullable|string|max:10',

                // Productos
                'products' => 'required|array|min:1',
                'products.*.id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
            ]);

            DB::beginTransaction();

            // Crear o recuperar usuario por email
            $user = AppUserModel::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['name'],
                    'surname' => $validated['surname'],
                    'phone' => $validated['phone'] ?? null,
                ]
            );

            // Crear dirección
            $address = AddressModel::create([
                'street' => $validated['street'],
                'number' => $validated['number'] ?? null,
                'city' => $validated['city'],
                'province' => $validated['province'],
                'postalCode' => $validated['postalCode'],
                'country' => $validated['country'],
                'floor' => $validated['floor'] ?? null,
            ]);

            // Asociar dirección al usuario (si tiene relación many-to-many)
            if (method_exists($user, 'addresses')) {
                $user->addresses()->attach($address->id, [
                    'name' => 'FastOrder Address'
                ]);
            }

            // Crear pedido
            $order = OrderModel::create([
                'date' => now(),
                'state' => 'pendiente',
                'userId' => $user->id,
                'addressId' => $address->id,
            ]);

            // Cargar productos y preparar sync
            $productIds = collect($validated['products'])->pluck('id');
            $products = Product::whereIn('id', $productIds)
                ->get()
                ->keyBy('id');

            $syncData = [];
            foreach ($validated['products'] as $item) {
                $product = $products->get($item['id']);
                if (!$product) {
                    throw new \Exception("Producto no encontrado: " . $item['id']);
                }
                $syncData[$product->id] = [
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            }

            $order->products()->sync($syncData);

            DB::commit();

            return new OrderResource(
                $order->load(['user', 'address', 'products'])
            );

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating FastOrder: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'error' => 'Error creating fast order'
            ], 500);
        }
    }
}