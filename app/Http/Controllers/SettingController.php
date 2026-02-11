<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Category; // Pastikan model ini sudah dibuat
use App\Models\Book;     // Pastikan model ini sudah dibuat
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SettingController extends Controller
{
    public function index()
{
    if (!\Illuminate\Support\Facades\Gate::allows('admin')) {
        abort(403);
    }

    try {
        // Menggunakan firstOrCreate agar data ID 1 otomatis ada
        $setting = Setting::firstOrCreate(['id' => 1], [
            'batas_hari' => 7,
            'denda_per_hari' => 1000,
            'daftar_kategori' => ['Novel', 'Sains', 'Biografi']
        ]);
    } catch (\Exception $e) {
        // Fallback jika tabel benar-benar belum ada di MySQL
        return "Tabel settings belum dibuat. Silakan jalankan 'php artisan migrate' di terminal.";
    }

    $books = \App\Models\Book::all();
    return view('settings.index', compact('setting', 'books'));
}

    // Update Denda & Batas Hari
   public function update(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'batas_hari' => 'required|numeric|min:1',
            'denda_per_hari' => 'required|numeric|min:0',
        ]);

        // 2. Gunakan updateOrCreate untuk memastikan data ID 1 yang diperbarui
        \App\Models\Setting::updateOrCreate(
            ['id' => 1], // Cari data dengan ID 1
            [
                'batas_hari' => $request->batas_hari,
                'denda_per_hari' => $request->denda_per_hari,
            ]
        );

        return redirect()->back()->with('success', 'Denda dan batas waktu berhasil diperbarui!');
    }

    // --- FITUR BARU: MANAJEMEN KATEGORI ---
   public function storeCategory(Request $request)
    {
        $setting = Setting::first();
        $categories = $setting->daftar_kategori ?? [];
        
        // Tambah kategori baru ke array
        $categories[] = $request->nama_kategori;
        
        $setting->update([
            'daftar_kategori' => array_unique($categories) // Pastikan tidak ada duplikat
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan ke pengaturan!');
    }

    public function destroyCategory($category) 
    {
        $setting = Setting::first();
        if ($setting && $setting->daftar_kategori) {
            $list = $setting->daftar_kategori;
            
            // Menghapus item dari array berdasarkan nama string
            $newList = array_values(array_filter($list, function($item) use ($category) {
                return $item !== $category;
            }));
            
            $setting->update(['daftar_kategori' => $newList]);
        }
        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }

    // --- FITUR BARU: REKOMENDASI BUKU ---
    public function toggleRecommendation($id)
    {
        $book = Book::findOrFail($id);
        // Toggle status: jika true jadi false, jika false jadi true
        $book->is_recommended = !$book->is_recommended;
        $book->save();

        return back()->with('success', 'Status rekomendasi buku ' . $book->judul . ' berhasil diperbarui!');
    }
}