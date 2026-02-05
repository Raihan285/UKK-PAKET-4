<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DaftarBukuController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- HALAMAN GUEST (Tanpa Login) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// --- HALAMAN PROTECTED (Wajib Login) ---
Route::middleware(['auth'])->group(function () {
    
    // Akses Semua Role (Admin & Siswa)
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/daftar-buku', [DaftarBukuController::class, 'index'])->name('daftar-buku.index');
    Route::get('/daftar-buku/{id}', [DaftarBukuController::class, 'show'])->name('daftar-buku.show');
    
    // Transaksi Dasar
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi', [TransactionController::class, 'store'])->name('transaksi.store');

    // --- KHUSUS ROLE ADMIN SAJA ---
    Route::middleware(['can:admin'])->group(function () {
        // Master Data
        Route::resource('buku', BookController::class);
        Route::resource('anggota', AnggotaController::class);

        // Manajemen Transaksi (Approve & Pengembalian)
        Route::post('/transaksi/{id}/approve', [TransactionController::class, 'approve'])->name('transaksi.approve');
        Route::get('/pengembalian', [TransactionController::class, 'pengembalian'])->name('transaksi.pengembalian');
        Route::post('/transaksi/{id}/process-return', [TransactionController::class, 'processReturn'])->name('transaksi.processReturn');

        // Pengaturan Sistem & Denda
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');

        // Manajemen Kategori (Menggunakan parameter {category} untuk menghindari error ID)
        Route::post('/settings/category', [SettingController::class, 'storeCategory'])->name('settings.category.store');
        Route::delete('/settings/category/{category}', [SettingController::class, 'destroyCategory'])->name('settings.category.destroy');

        // Rekomendasi Buku
        Route::post('/settings/book-recommendation/{id}', [SettingController::class, 'toggleRecommendation'])->name('settings.recommendation.toggle');
    });
});