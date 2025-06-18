<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_cost', 10, 2);
            $table->decimal('total', 12, 2);
            $table->string('status')->default('pending'); // pending, processing, shipped, delivered, cancelled
            
            // Shipping Information
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->text('shipping_address');
            $table->string('city');
            $table->string('district');
            $table->string('postal_code');
            $table->string('courier_name');
            $table->string('courier_service');
            $table->string('tracking_number')->nullable();
            
            // Biteship Integration
            $table->json('courier_details')->nullable();
            $table->string('biteship_order_id')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
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