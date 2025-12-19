<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $discountType = fake()->randomElement(['percentage', 'fixed']);
        $discountAmount = $discountType === 'percentage'
            ? fake()->numberBetween(5, 50)
            : fake()->randomFloat(2, 10000, 100000);

        return [
            'code' => strtoupper(fake()->unique()->bothify('DISCOUNT##')),
            'discount_amount' => $discountAmount,
            'discount_type' => $discountType,
            'minimum_order_amount' => fake()->randomFloat(2, 50000, 200000),
            'max_usage' => fake()->numberBetween(0, 100),
            'max_usage_per_user' => fake()->numberBetween(1, 5),
            'start_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'end_date' => fake()->dateTimeBetween('now', '+3 months'),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the discount is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the discount is percentage type.
     */
    public function percentage(): static
    {
        return $this->state(fn(array $attributes) => [
            'discount_type' => 'percentage',
            'discount_amount' => fake()->numberBetween(5, 50),
        ]);
    }

    /**
     * Indicate that the discount is fixed type.
     */
    public function fixed(): static
    {
        return $this->state(fn(array $attributes) => [
            'discount_type' => 'fixed',
            'discount_amount' => fake()->randomFloat(2, 10000, 100000),
        ]);
    }
}

