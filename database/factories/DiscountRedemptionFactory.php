<?php

namespace Database\Factories;

use App\Models\Discount;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DiscountRedemption>
 */
class DiscountRedemptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'discount_id' => Discount::factory(),
            'user_id' => User::factory(),
            'order_id' => Order::factory(),
            'status' => fake()->randomElement(['pending', 'success', 'cancelled']),
        ];
    }

    /**
     * Indicate that the redemption is successful.
     */
    public function success(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'success',
        ]);
    }

    /**
     * Indicate that the redemption is pending.
     */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the redemption is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}

