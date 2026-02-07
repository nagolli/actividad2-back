<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $width = 400;
        $height = 400;
        $randomId = $this->faker->numberBetween(1, 100000);

        return [
            'name' => $this->faker->unique()->word(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->sentence(),
            'stock' => $this->faker->numberBetween(0, 100),
            'image' => "https://picsum.photos/seed/{$randomId}/{$width}/{$height}",
            'inactive' => $this->faker->boolean(),
            'categoryId' => Category::factory(),
            'supplierId' => Supplier::factory(),
        ];
    }
}
