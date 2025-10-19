<?php

namespace Database\Factories;

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
        return [
            //
            'name' => fake()->name(),
            'description' => fake()->sentence(),
            'image_product' => fake()->imageUrl(),
            'stock' => fake()->numberBetween(1, 100),
            'price' => (int) round(fake()->randomFloat(2, 10000, 1000000)), // ✅ Harus integer untuk IDR
        ];
    }
}
