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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            
            $table->date('tanggal_pinjam')->nullable(); 
            $table->date('tanggal_kembali')->nullable();
            $table->date('tanggal_pengembalian')->nullable(); 
            $table->decimal('denda', 10, 2)->default(0); 
            
            // Status lengkap untuk menghindari error SQL
            // Pastikan hanya satu tanda $
            $table->enum('status', ['menunggu', 'dipinjam', 'menunggu_kembali', 'kembali'])->default('menunggu');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
