<?php

namespace App\Http\Controllers;

use App\Models\Book; 
use App\Models\Setting; // Penting untuk mengambil daftar_kategori
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Menampilkan daftar buku dan mengirimkan data kategori dinamis
     */
  public function index()
    {
        // 1. Ambil data buku (pastikan nama variabel sesuai dengan di View)
        $buku = \App\Models\Book::all(); 
        
        // 2. Ambil data setting untuk mendapatkan kategori
        $setting = \App\Models\Setting::first();
        
        // 3. Ambil kategori dan bersihkan dari nilai null/kosong
        $categories = array_filter($setting->daftar_kategori ?? []); 

        // 4. Kirim variabel ke View
        return view('buku.index', compact('buku', 'categories'));
    }

    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'kategori' => 'required',
            'stok' => 'required|numeric',
            'cover' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Simpan file cover ke storage public
        $path = $request->file('cover')->store('covers', 'public');

        // 3. Simpan data ke database termasuk kolom is_recommended
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
            // Hapus file lama jika ada upload file baru
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