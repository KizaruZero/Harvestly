<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = ['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Semarang', 'Medan', 'Makassar'];
        $provinces = ['DKI Jakarta', 'Jawa Barat', 'Jawa Timur', 'DI Yogyakarta', 'Jawa Tengah', 'Sumatera Utara', 'Sulawesi Selatan'];

        return [
            'user_id' => User::factory(),
            'address' => fake()->streetAddress(),
            'city' => fake()->randomElement($cities),
            'province' => fake()->randomElement($provinces),
            'postal_code' => fake()->postcode(),
            'country' => 'Indonesia',
            'is_default' => false,
            'recipient_name' => fake()->name(),
            'phone_number' => fake()->numerify('08##########'),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the address is default.
     */
    public function default(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_default' => true,
        ]);
    }
}

