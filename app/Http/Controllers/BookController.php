<?php

namespace App\Http\Controllers;

use App\Models\Book; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Menampilkan daftar buku dan mengirimkan data kategori 
     */
  public function index()
    {
        // Ambil Data Buku
        $buku = \App\Models\Book::all(); 
        
        // Ambil Data Setting Untuk Mendapatkan Kategori
        $setting = \App\Models\Setting::first();
        
        // Ambil Kategori Dan Bersihkan Dari Nilai Null
        $categories = array_filter($setting->daftar_kategori ?? []); 

        // Mengirim Variabel Ke View
        return view('buku.index', compact('buku', 'categories'));
    }

    public function store(Request $request)
    {
        // Validasi Input
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'kategori' => 'required',
            'stok' => 'required|numeric',
            'cover' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Simpan File Cover Ke Storage Public
        $path = $request->file('cover')->store('covers', 'public');

        // Simpan Data Ke Database
        Book::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'cover' => $path,
            'is_recommended' => false, 
        ]);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $buku = Book::findOrFail($id);

        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'kategori' => 'required',
            'stok' => 'required|numeric',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('cover')) {
            // Logika file lama jika ada upload file baru
            if ($buku->cover) {
                Storage::disk('public')->delete($buku->cover);
            }
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $buku->update($data);

        return redirect()->back()->with('success', 'Data buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $buku = Book::findOrFail($id);

        if ($buku->cover) {
            Storage::disk('public')->delete($buku->cover);
        }

        $buku->delete();

        return redirect()->back()->with('success', 'Buku berhasil dihapus!');
    }
}