<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Mengubah kolom yang relevan agar bisa NULL
            $table->string('payment_proof')->nullable()->change();
            $table->text('shipping_address')->nullable()->change();
            $table->string('postal_code')->nullable()->change();
            $table->string('courier_name')->nullable()->change();
            $table->string('courier_service')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Method untuk rollback jika diperlukan
            $table->string('payment_proof')->nullable(false)->change();
            $table->text('shipping_address')->nullable(false)->change();
            $table->string('postal_code')->nullable(false)->change();
            $table->string('courier_name')->nullable(false)->change();
            $table->string('courier_service')->nullable(false)->change();
        });
    }
};