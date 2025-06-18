<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('obat')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable(); // For guest checkouts
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->text('recipient_address');
            $table->string('recipient_province');
            $table->string('recipient_city');
            $table->string('recipient_district');
            $table->string('recipient_postal_code', 5);
            $table->string('courier');
            $table->string('courier_service');
            $table->decimal('product_price', 15, 2);
            $table->decimal('shipping_cost', 15, 2);
            $table->decimal('total_price', 15, 2);
            $table->decimal('weight_in_grams', 10, 2);
            $table->string('status')->default('pending'); // e.g., pending, paid, shipped, completed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};