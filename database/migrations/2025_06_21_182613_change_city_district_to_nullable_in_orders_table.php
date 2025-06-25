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
        Schema::table('orders', function (Blueprint $table) {
            // Mengubah kolom agar bisa menerima nilai NULL
            $table->string('city')->nullable()->change();
            $table->string('district')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Mengembalikan kolom ke state semula (tidak bisa NULL) jika di-rollback
            // Pastikan untuk memberi nilai default jika ada data yang sudah NULL
            $table->string('city')->nullable(false)->change();
            $table->string('district')->nullable(false)->change();
        });
    }
};