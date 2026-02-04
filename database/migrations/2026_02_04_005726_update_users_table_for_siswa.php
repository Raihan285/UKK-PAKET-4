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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom profile yang kurang
            if (!Schema::hasColumn('users', 'telepon')) {
                $table->string('telepon')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'alamat')) {
                $table->string('alamat')->nullable()->after('telepon');
            }
            
            // Membuat kolom username menjadi nullable jika Anda tidak ingin memakainya
            // atau tetap memakainya dengan mengisi data email ke sana
            $table->string('username')->nullable()->change(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
