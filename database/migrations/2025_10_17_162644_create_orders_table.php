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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->foreignId('user_id');
            $table->string('metode_pembayaran', 100)->nullable();
            $table->decimal('subtotal_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'paid', 'expired', 'cancelled', 'packed', 'shipped', 'delivered', 'completed'])->default('pending');
            $table->string('snap_token')->nullable();
            $table->foreignId('address_id')->constrained('addresses');
            $table->foreignId('discount_id')->constrained('discounts');
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('promo_code_snapshot', 100)->nullable();
            $table->string('shipping_method', 100)->nullable();
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->string('notes', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
