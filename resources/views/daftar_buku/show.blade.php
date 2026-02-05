@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4">
    <a href="{{ route('daftar-buku.index') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-gray-900 mb-8 transition-colors group">
        <i class="ph ph-arrow-left font-bold"></i>
        <span class="text-[10px] font-bold uppercase tracking-widest">Kembali ke Daftar Buku</span>
    </a>

    <div class="bg-white rounded-[2.5rem] border border-gray-100 p-8 md:p-12 shadow-sm flex flex-col md:flex-row gap-12 items-start">
        <div class="w-full md:w-1/3 shrink-0">
            <div class="aspect-[3/4] rounded-3xl overflow-hidden shadow-2xl transform md:-rotate-2 hover:rotate-0 transition-transform duration-500">
                <img src="{{ asset('storage/' . $book->cover) }}" class="w-full h-full object-cover">
            </div>
        </div>

        <div class="flex-1 w-full space-y-6">
            <div>
                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[9px] font-black uppercase tracking-widest">{{ $book->kategori }}</span>
                <h1 class="text-3xl font-black text-gray-900 mt-4 leading-tight">{{ $book->judul }}</h1>
                <p class="text-gray-400 text-sm font-medium mt-2 italic">Ditulis oleh <span class="text-gray-600 font-bold">{{ $book->penulis }}</span></p>
            </div>

            <div class="grid grid-cols-2 gap-4 py-6 border-y border-gray-50">
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Stok Tersedia</p>
                    <p class="text-sm font-black text-gray-800">{{ $book->stok }} Eksemplar</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Status Buku</p>
                    <p class="text-sm font-black {{ $book->stok > 0 ? 'text-emerald-500' : 'text-red-500' }}">
                        {{ $book->stok > 0 ? 'Siap Dipinjam' : 'Tidak Tersedia' }}
                    </p>
                </div>
            </div>

            <div class="pt-4">
                @if($book->stok > 0)
                <form action="{{ route('transaksi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    <button type="submit" class="w-full md:w-auto px-10 py-4 bg-gray-900 text-white rounded-2xl font-bold text-xs hover:bg-blue-600 hover:shadow-lg transition-all flex items-center justify-center gap-3">
                        <i class="ph ph-hand-pointing text-lg"></i>
                        Ajukan Pinjam Buku
                    </button>
                </form>
                <p class="mt-4 text-[9px] text-gray-400 italic font-medium leading-relaxed">
                    * Berdasarkan peraturan perpustakaan, setiap peminjaman harus melalui persetujuan admin sebelum buku dapat diambil fisik.
                </p>
                @else
                <button disabled class="w-full md:w-auto px-10 py-4 bg-gray-100 text-gray-400 rounded-2xl font-bold text-xs cursor-not-allowed">
                    Stok Habis
                </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection