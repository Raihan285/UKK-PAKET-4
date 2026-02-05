@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-8"> 
    <div class="flex flex-col md:flex-row justify-between items-end gap-4 border-b border-gray-100 pb-6">
        <div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight">Rekomendasi Buku</h2>
            <p class="text-gray-400 text-[11px] font-medium tracking-wider">Temukan inspirasi baca kamu!</p>
        </div>
        
        <div class="flex flex-wrap gap-1.5">
            <button class="px-3 py-1.5 bg-red-50 text-red-500 rounded-md text-[9px] font-bold">Direkomendasikan</button>
            @foreach(['Agama', 'Sejarah', 'Humor', 'Administrasi', 'Ensiklopedia'] as $kat)
                <button class="px-3 py-1.5 bg-gray-50 text-gray-400 rounded-md text-[9px] font-bold hover:bg-gray-100 transition-all">{{ $kat }}</button>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @forelse($books as $book)
        <a href="{{ route('daftar-buku.show', $book->id) }}" class="group relative aspect-[1/1.4] overflow-hidden rounded-2xl shadow-sm transition-all duration-500 hover:-translate-y-1 hover:shadow-xl bg-gray-100 block">
            
            <img src="{{ asset('storage/' . $book->cover) }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
            
            <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/10 to-transparent opacity-90"></div>

            <div class="absolute inset-0 p-4 flex flex-col justify-end items-center text-center">
                
                <span class="mb-2 px-2 py-1 bg-blue-600/90 backdrop-blur-sm text-white text-[8px] font-bold rounded-sm uppercase tracking-tighter">
                    {{ $book->kategori ?? 'Fiksi / Novel' }}
                </span>

                <h4 class="text-white font-bold text-sm leading-tight mb-1 line-clamp-2">
                    {{ $book->judul }}
                </h4>

                <p class="text-gray-300 text-[9px] font-medium opacity-70 line-clamp-1">
                    {{ $book->penulis }}
                </p>

                <div class="mt-3 overflow-hidden h-0 group-hover:h-8 transition-all duration-300 w-full">
                    <div class="w-full py-1.5 bg-white text-gray-900 rounded-lg font-bold text-[9px] shadow-lg flex items-center justify-center">
                        Lihat Detail
                    </div>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full py-20 text-center text-gray-400 text-xs italic">
            Belum ada koleksi buku.
        </div>
        @endforelse
    </div>
</div>
@endsection