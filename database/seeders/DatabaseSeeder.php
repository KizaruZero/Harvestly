<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Hash;
use Illuminate\Database\Seeder;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        Product::factory(10)->create();
        User::factory()->create([
            'name' => 'Kizaru',
            'email' => 'kizarukaede@gmail.com',
            'password' => Hash::make('password')
        ]);
    }
}
