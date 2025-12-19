<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(10, 1000);
        $reservedQuantity = fake()->numberBetween(0, $quantity);

        return [
            'product_id' => Product::factory(),
            'stock' => $quantity,
            'reserved_stock' => $reservedQuantity,
        ];
    }

    /**
     * Indicate that the inventory is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn(array $attributes) => [
            'stock' => 0,
            'reserved_stock' => 0,
        ]);
    }
}

