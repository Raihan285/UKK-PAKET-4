<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Utama
        \App\Models\User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // 2. Data Pengaturan Awal (Penting untuk denda)
        \App\Models\Setting::create([
            'batas_hari' => 7,
            'denda_per_hari' => 1000,
            'daftar_kategori' => ['Novel', 'Sains', 'Sejarah', 'Komik']
        ]);

        // 3. Contoh Buku (Agar katalog tidak kosong)
        \App\Models\Book::create([
            'judul' => 'Panduan Laravel 11',
            'penulis' => 'Taylor Otwell',
            'stok' => 10,
            'kategori' => 'Sains'
        ]);
    }
    }
