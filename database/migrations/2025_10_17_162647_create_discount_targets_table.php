<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('discount_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')->constrained('discounts')->nullable();
            $table->foreignId('product_id')->constrained('products')->nullable();
            $table->foreignId('category_id')->constrained('categories')->nullable();
            $table->foreignId('user_id')->constrained('users')->nullable();
            $table->enum('target_type', ['product', 'category', 'user']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_targets');
    }
};
