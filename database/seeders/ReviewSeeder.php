<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\AppUser;
use App\Models\Product;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = AppUser::all();
        $products = Product::all();

        foreach ($users as $user) {
            // cada usuario reseña 3-5 productos distintos
            $randomProducts = $products->random(rand(3, 5));

            foreach ($randomProducts as $product) {
                Review::factory()->create([
                    'appUser' => $user->id,
                    'productId' => $product->id,
                ]);
            }
        }
    }
}

