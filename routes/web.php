<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\TransactionController;
use App\Models\Book; // Pastikan penulisan Model konsisten (Book, bukan book)
use App\Http\Controllers\AnggotaController;

// --- HALAMAN GUEST ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// --- HALAMAN PROTECTED (Wajib Login) ---
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Utama
    Route::get('/', function () {
        $books = Book::all(); 
        return view('home', compact('books'));
    })->name('home');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- FITUR KELOLA BUKU (CRUD) ---
    // Menggunakan resource agar mendukung index, create, store, edit, update, dan destroy otomatis
    Route::resource('buku', BookController::class);

    // Transaksi
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transaksi.index');
    
    // Anggota
    Route::resource('anggota', AnggotaController::class);
});