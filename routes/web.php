<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DaftarBukuController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DashboardController;

// Akses sebelum login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Halaman wajib login 
Route::middleware(['auth'])->group(function () {
    
    // Beranda & Logout
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Katalog Buku (Akses: Admin & Siswa)
    Route::get('/daftar-buku', [DaftarBukuController::class, 'index'])->name('daftar_buku.index');
    Route::get('/daftar-buku/{id}', [DaftarBukuController::class, 'show'])->name('daftar_buku.show');
    
    // Sirkulasi Dasar (Akses: Admin & Siswa)
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi', [TransactionController::class, 'store'])->name('transaksi.store');

    // Logika Pengembalian: Siswa
    Route::post('/transaksi/{id}/ajukan-kembali', [TransactionController::class, 'ajukanPengembalian'])->name('transaksi.ajukan_kembali');

    // ADMIN
    Route::middleware(['can:admin'])->group(function () {
        
        // Dashboard Admin
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Manajemen Master Data
        Route::resource('buku', BookController::class);
        Route::resource('anggota', AnggotaController::class);

        // Verifikasi & Pengolahan Sirkulasi
        Route::post('/transaksi/{id}/approve', [TransactionController::class, 'approve'])->name('transaksi.approve');
        Route::get('/pengembalian', [TransactionController::class, 'pengembalian'])->name('transaksi.pengembalian');
        Route::post('/transaksi/{id}/return', [TransactionController::class, 'returnBook'])->name('transaksi.return');
        Route::post('/transaksi/{id}/konfirmasi-kembali', [TransactionController::class, 'processReturn'])->name('transaksi.konfirmasi_kembali'); 
        
        // Pengaturan Sistem (Denda & Batas Waktu)
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');

        //  Manajemen Kategori Dinamis
        Route::post('/settings/category', [SettingController::class, 'storeCategory'])->name('settings.category.store');
        Route::delete('/settings/category/{category}', [SettingController::class, 'destroyCategory'])->name('settings.category.destroy');

        //  Rekomendasi
        Route::post('/settings/book-recommendation/{id}', [SettingController::class, 'toggleRecommendation'])->name('settings.recommendation.toggle');
    });
});