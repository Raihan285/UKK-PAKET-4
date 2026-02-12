<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class DaftarBukuController extends Controller
{
    public function index(Request $request)
    {
        $kategori_dipilih = $request->query('kategori');
        $query = \App\Models\Book::query();

        if ($kategori_dipilih) {
            $query->where('kategori', $kategori_dipilih);
        }

        $books = $query->get();


        try {
            $setting = \App\Models\Setting::first() ?: (object)[
                'batas_hari' => 7, 
                'denda_per_hari' => 1000
            ];
        } catch (\Exception $e) {
            $setting = (object)[
                'batas_hari' => 7, 
                'denda_per_hari' => 1000
            ];
        }

        return view('daftar_buku.index', compact('books', 'setting', 'kategori_dipilih'));
    }

    public function show($id)
    {
        $book = \App\Models\Book::findOrFail($id);
        return view('daftar_buku.show', compact('book'));
    }
}