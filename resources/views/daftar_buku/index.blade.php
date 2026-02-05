@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="flex justify-between items-end border-b border-gray-100 pb-6">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Daftar Buku</h2>
            <p class="text-gray-400 text-[10px] font-bold tracking-widest">Berikut adalah daftar buku yang tersedia di perpustakaan kami</p>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($books as $book)
        <a href="{{ route('daftar-buku.show', $book->id) }}" class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-500 block">
            <div class="aspect-[3/4] overflow-hidden relative">
                <img src="{{ asset('storage/' . $book->cover) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-4">
                    <span class="bg-white text-gray-900 px-4 py-2 rounded-lg text-[9px] font-bold shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform">
                        Detail Buku
                    </span>
                </div>
            </div>

            <div class="p-3">
                <span class="text-[8px] font-bold text-blue-500 uppercase">{{ $book->kategori }}</span>
                <h4 class="text-[11px] font-bold text-gray-900 leading-tight line-clamp-2 h-7 mt-1">{{ $book->judul }}</h4>
                <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-50">
                    <p class="text-[9px] text-gray-400 font-medium">Stok: {{ $book->stok }}</p>
                    <i class="ph ph-bookmark-simple text-gray-300 group-hover:text-blue-500 transition-colors"></i>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection