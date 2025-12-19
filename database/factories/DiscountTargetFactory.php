<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DiscountTarget>
 */
class DiscountTargetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $targetType = fake()->randomElement(['product', 'category', 'user']);

        return [
            'discount_id' => Discount::factory(),
            'product_id' => $targetType === 'product' ? Product::factory() : null,
            'category_id' => $targetType === 'category' ? Category::factory() : null,
            'user_id' => $targetType === 'user' ? User::factory() : null,
            'target_type' => $targetType,
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the target is a product.
     */
    public function product(): static
    {
        return $this->state(fn(array $attributes) => [
            'target_type' => 'product',
            'product_id' => Product::factory(),
            'category_id' => null,
            'user_id' => null,
        ]);
    }

    /**
     * Indicate that the target is a category.
     */
    public function category(): static
    {
        return $this->state(fn(array $attributes) => [
            'target_type' => 'category',
            'product_id' => null,
            'category_id' => Category::factory(),
            'user_id' => null,
        ]);
    }

    /**
     * Indicate that the target is a user.
     */
    public function user(): static
    {
        return $this->state(fn(array $attributes) => [
            'target_type' => 'user',
            'product_id' => null,
            'category_id' => null,
            'user_id' => User::factory(),
        ]);
    }
}

