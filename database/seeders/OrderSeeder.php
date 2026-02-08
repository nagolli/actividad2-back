<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        Order::factory(10)->create()->each(function ($order) {

            // coger 2-4 productos aleatorios
            $products = Product::inRandomOrder()->take(rand(2,4))->get();
            foreach ($products as $product) {
              //  print("Pedido " . $order->id . " Producto " . $product->id);
                $order->products()->attach($product->id, [
                    'quantity' => rand(1,5),
                    'price' => $product->price
                ]);
            }
        });
    }
}
