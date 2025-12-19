<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Discount;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotalPrice = fake()->randomFloat(2, 50000, 500000);
        $discountAmount = fake()->randomFloat(2, 0, $subtotalPrice * 0.3);
        $shippingCost = fake()->randomFloat(2, 10000, 50000);
        $totalPrice = $subtotalPrice - $discountAmount + $shippingCost;

        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'metode_pembayaran' => fake()->randomElement(['credit_card', 'bank_transfer', 'e_wallet', 'cod']),
            'subtotal_price' => $subtotalPrice,
            'total_price' => $totalPrice,
            'status' => fake()->randomElement(['pending', 'paid', 'expired', 'cancelled', 'packed', 'shipped', 'delivered', 'completed']),
            'snap_token' => fake()->optional()->sha256(),
            'address_id' => Address::factory(),
            'discount_id' => Discount::factory(),
            'discount_amount' => $discountAmount,
            'promo_code_snapshot' => fake()->optional()->bothify('PROMO##'),
            'shipping_method' => fake()->randomElement(['regular', 'express', 'same_day']),
            'shipping_cost' => $shippingCost,
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the order is pending.
     */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the order is paid.
     */
    public function paid(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'paid',
        ]);
    }

    /**
     * Indicate that the order is completed.
     */
    public function completed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'completed',
        ]);
    }
}

