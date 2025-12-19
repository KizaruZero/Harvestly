<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'review' => fake()->optional()->paragraph(),
            'is_active' => true,
            'like_count' => fake()->numberBetween(0, 100),
            'dislike_count' => fake()->numberBetween(0, 20),
        ];
    }

    /**
     * Indicate that the review has 5 stars.
     */
    public function fiveStars(): static
    {
        return $this->state(fn(array $attributes) => [
            'rating' => 5,
        ]);
    }

    /**
     * Indicate that the review is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}

