<?php

namespace App\Http\Controllers;

// Pastikan Model Book sudah di-import dengan benar
use App\Models\Book; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Menampilkan daftar buku (Halaman Kelola Buku)
     * Mengatasi error Call to undefined method
     */
    public function index()
    {
        // Ambil semua data dari database
        // Gunakan nama variabel $buku agar sinkron dengan View
        $buku = Book::all(); 
        
        // Mengirim variabel $buku ke resources/views/buku/index.blade.php
        return view('buku.index', compact('buku'));
    }

    public function create()
    {
        return view('buku.create');
    }

    public function store(Request $request)
    {
    // 1. Validasi Input
    $request->validate([
        'judul' => 'required',
        'penulis' => 'required',
        'kategori' => 'required',
        'stok' => 'required|numeric',
        'cover' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // 2. Upload Gambar Cover
    $path = $request->file('cover')->store('covers', 'public');

    // 3. Simpan ke Database
    \App\Models\Book::create([
        'judul' => $request->judul,
        'penulis' => $request->penulis,
        'kategori' => $request->kategori,
        'stok' => $request->stok,
        'cover' => $path,
    ]);

    return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
}
    public function destroy($id)
{
    $buku = \App\Models\Book::findOrFail($id);

    if ($buku->cover) {
        // Menggunakan backslash agar langsung memanggil global class Laravel
        \Illuminate\Support\Facades\Storage::disk('public')->delete($buku->cover);
    }

    $buku->delete();

    return redirect()->back()->with('success', 'Buku berhasil dihapus!');
}

public function update(Request $request, $id)
{
    $buku = \App\Models\Book::findOrFail($id);

    $request->validate([
        'judul' => 'required',
        'penulis' => 'required',
        'kategori' => 'required',
        'stok' => 'required|numeric',
        'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $data = $request->all();

    if ($request->hasFile('cover')) {
        // Hapus cover lama jika ada file baru yang diupload
        if ($buku->cover) {
            Storage::disk('public')->delete($buku->cover);
        }
        $data['cover'] = $request->file('cover')->store('covers', 'public');
    }

    $buku->update($data);

    return redirect()->back()->with('success', 'Data buku berhasil diperbarui!');
}
}