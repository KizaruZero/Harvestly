<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductCategory;
use App\Models\Review;
use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Kizaru',
            'email' => 'kizarukaede@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // Create regular users
        $users = User::factory(20)->create();

        // Create categories
        $categories = Category::factory(10)->create();
        $featuredCategories = Category::factory(3)->featured()->create();

        // Create products with relationships
        $products = Product::factory(50)->create();

        // Create featured products
        $featuredProducts = Product::factory(10)->featured()->create();

        // Create product images (multiple images per product)
        foreach ($products->merge($featuredProducts) as $product) {
            ProductImage::factory()->primary()->create(['product_id' => $product->id]);
            ProductImage::factory(rand(2, 5))->create(['product_id' => $product->id]);
        }

        // Create product categories (many-to-many relationship)
        foreach ($products->take(30) as $product) {
            ProductCategory::factory()->create([
                'product_id' => $product->id,
                'category_id' => $categories->random()->id,
            ]);
        }

        // Create inventories for products
        foreach ($products->merge($featuredProducts) as $product) {
            Inventory::factory()->create(['product_id' => $product->id]);
        }

        // Create addresses for users
        foreach ($users->take(15) as $user) {
            Address::factory()->default()->create(['user_id' => $user->id]);
            Address::factory(rand(1, 3))->create(['user_id' => $user->id]);
        }

        // Create discounts
        $discounts = Discount::factory(10)->create();
        Discount::factory(5)->percentage()->create();
        Discount::factory(5)->fixed()->create();

        // Create orders
        $orders = Order::factory(30)->create();
        Order::factory(10)->paid()->create();
        Order::factory(5)->completed()->create();

        // Create reviews
        foreach ($products->take(20) as $product) {
            Review::factory(rand(3, 10))->create([
                'product_id' => $product->id,
                'user_id' => $users->random()->id,
            ]);
        }

        // Create some 5-star reviews
        Review::factory(15)->fiveStars()->create();

        // Create carts for some users
        foreach ($users->take(10) as $user) {
            $cart = Cart::factory()->create(['user_id' => $user->id]);
            Cart::factory()->create(['user_id' => $user->id]);
        }
    }
}
