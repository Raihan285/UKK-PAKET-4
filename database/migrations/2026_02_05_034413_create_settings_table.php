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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            // Pengaturan Denda & Waktu
            $table->integer('batas_hari')->default(7);
            $table->decimal('denda_per_hari', 10, 2)->default(1000);
            
            // Pengaturan Kategori (Disatukan di sini sebagai teks/JSON)
            $table->text('daftar_kategori')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
