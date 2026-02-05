<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class DaftarBukuController extends Controller
{
    public function index()
    {
        $books = Book::all(); 
        return view('daftar_buku.index', compact('books'));
    }

    public function show($id)
    {
        $book = \App\Models\Book::findOrFail($id);
        return view('daftar_buku.show', compact('book'));
    }
}