<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DaftarBukuController;
use App\Http\Controllers\SettingController;


// --- HALAMAN GUEST (Akses sebelum Login) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// --- HALAMAN PROTECTED (Wajib Login) ---
Route::middleware(['auth'])->group(function () {
    
    // 1. Beranda & Logout
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // 2. Katalog Buku (Akses: Admin & Siswa)
    // Memperbaiki error image_a10ade.png: pastikan penamaan route konsisten
    Route::get('/daftar-buku', [DaftarBukuController::class, 'index'])->name('daftar_buku.index');
    // Memperbaiki error image_a1196d.png: pastikan method show() ada di controller
    Route::get('/daftar-buku/{id}', [DaftarBukuController::class, 'show'])->name('daftar_buku.show');
    
    // 3. Sirkulasi Dasar (Akses: Admin & Siswa)
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi', [TransactionController::class, 'store'])->name('transaksi.store');

    // 4. Logika Pengembalian: Siswa (Akses: Siswa)
    // Di luar middleware 'can:admin' agar siswa bisa mengirim permintaan kembali
    Route::post('/transaksi/{id}/ajukan-kembali', [TransactionController::class, 'ajukanPengembalian'])->name('transaksi.ajukan_kembali');

    // --- KHUSUS ROLE ADMIN SAJA ---
    Route::middleware(['can:admin'])->group(function () {
        
        // A. Manajemen Master Data
        Route::resource('buku', BookController::class);
        Route::resource('anggota', AnggotaController::class);

        // B. Verifikasi & Pengolahan Sirkulasi
        // Approve Peminjaman
        Route::post('/transaksi/{id}/approve', [TransactionController::class, 'approve'])->name('transaksi.approve');
        
        // Halaman Verifikasi Pengembalian (Memperbaiki error image_a1f782.png)
        Route::get('/pengembalian', [TransactionController::class, 'pengembalian'])->name('transaksi.pengembalian');
        Route::post('/transaksi/{id}/return', [TransactionController::class, 'returnBook'])->name('transaksi.return');
        
        // Proses Final Pengembalian & Hitung Denda (Memperbaiki error image_a1ebe3.png)
        Route::post('/transaksi/{id}/konfirmasi-kembali', [TransactionController::class, 'processReturn'])->name('transaksi.konfirmasi_kembali'); 
        
        // C. Pengaturan Sistem (Denda & Batas Waktu)
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');

        // D. Manajemen Kategori Dinamis
        Route::post('/settings/category', [SettingController::class, 'storeCategory'])->name('settings.category.store');
        Route::delete('/settings/category/{category}', [SettingController::class, 'destroyCategory'])->name('settings.category.destroy');

        // E. Fitur Tambahan: Rekomendasi
        Route::post('/settings/book-recommendation/{id}', [SettingController::class, 'toggleRecommendation'])->name('settings.recommendation.toggle');
    });
});