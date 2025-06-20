<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
           
            $table->enum('status', [
    'pending',
    'pending_verification',
    'processing',
    'ready_for_pickup',
    'shipped',
    'completed',
    'cancelled',
    'payment_rejected',
    'menunggu_pickup'
])->default('pending');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([ 'status']);
            // Tidak perlu rollback enum status jika tidak diubah
        });
    }
};