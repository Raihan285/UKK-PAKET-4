@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-12 py-6 px-4">
    
    {{-- 1. HEADER & FILTER KATEGORI --}}
    <div class="space-y-8">
        <div class="flex flex-col md:flex-row justify-between items-end gap-6">
            <div>
                <h2 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Daftar Buku</h2>
                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-[0.3em] mt-2 border-l-2 border-blue-600 pl-3">
                    @if($kategori_dipilih) 
                        Kategori: <span class="text-blue-600">{{ $kategori_dipilih }}</span>
                    @else 
                        Temukan buku di perpustakaan kami.
                    @endif
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('daftar_buku.index') }}" 
                   class="px-4 py-2 rounded-xl text-[9px] font-black transition-all uppercase tracking-widest {{ !$kategori_dipilih ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}">
                    Semua
                </a>
                @php $kategoris = array_filter($setting->daftar_kategori ?? []); @endphp
                @foreach($kategoris as $kat)
                    <a href="{{ route('daftar_buku.index', ['kategori' => $kat]) }}" 
                       class="px-4 py-2 rounded-xl text-[9px] font-black transition-all uppercase tracking-widest {{ $kategori_dipilih == $kat ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}">
                        {{ $kat }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 2. SECTION: BUKU REKOMENDASI --}}
    @if(!$kategori_dipilih)
    <section class="space-y-8">
        <div class="flex items-center gap-4">
            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Rekomendasi</h3>
            <div class="h-[1px] flex-1 bg-gray-100"></div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @foreach($books->where('is_recommended', true) as $book)
            {{-- Tambahkan class searchable-item di sini --}}
            <a href="{{ route('daftar_buku.show', $book->id) }}" class="searchable-item group relative aspect-[1/1.5] overflow-hidden rounded-[2.2rem] shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl bg-gray-100 block">
                
                <img src="{{ asset('storage/' . $book->cover) }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="absolute inset-0 p-5 flex flex-col justify-end">
                    <div class="flex justify-between items-start mb-2">
                        <span class="px-2 py-1 bg-amber-400 text-white text-[7px] font-black rounded-md uppercase tracking-widest">★ Rekomendasi</span>
                    </div>

                    <h4 class="text-white font-black text-[11px] leading-tight group-hover:text-blue-300 transition-colors line-clamp-2">
                        {{ $book->judul }}
                    </h4>
                    <p class="text-gray-300 text-[8px] font-bold opacity-80 mt-1 uppercase tracking-tighter">
                        {{ $book->penulis }} | Stok: {{ $book->stok }}
                    </p>

                    <div class="max-h-0 overflow-hidden group-hover:max-h-12 group-hover:mt-4 transition-all duration-500 ease-in-out">
                        <div class="w-full py-2.5 bg-white text-gray-900 rounded-xl font-black text-[9px] flex items-center justify-center uppercase shadow-2xl">
                            Lihat Detail
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- 3. SECTION: DAFTAR BUKU LAINNYA --}}
    <section class="space-y-8">
        <div class="flex items-center gap-4">
            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">
                {{ $kategori_dipilih ? 'Hasil Pencarian' : 'Koleksi Lainnya' }}
            </h3>
            <div class="h-[1px] flex-1 bg-gray-100"></div>
        </div> 

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @forelse($books->where('is_recommended', false) as $book)
            {{-- Tambahkan class searchable-item di sini juga --}}
            <a href="{{ route('daftar_buku.show', $book->id) }}" class="searchable-item group relative aspect-[1/1.5] overflow-hidden rounded-[2.2rem] shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl bg-gray-100 block">
                
                <img src="{{ asset('storage/' . $book->cover) }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="absolute inset-0 p-5 flex flex-col justify-end">
                    <span class="mb-2 w-fit px-2 py-1 bg-blue-600 text-white text-[7px] font-black rounded-md uppercase tracking-widest">
                        {{ $book->kategori ?? 'Umum' }}
                    </span>

                    <h4 class="text-white font-black text-[11px] leading-tight group-hover:text-blue-300 transition-colors line-clamp-2">
                        {{ $book->judul }}
                    </h4>
                    <p class="text-gray-300 text-[8px] font-bold opacity-80 mt-1 uppercase tracking-tighter">
                        {{ $book->penulis }} | Stok: {{ $book->stok }}
                    </p>

                    <div class="max-h-0 overflow-hidden group-hover:max-h-12 group-hover:mt-4 transition-all duration-500 ease-in-out">
                        <div class="w-full py-2.5 bg-white text-gray-900 rounded-xl font-black text-[9px] flex items-center justify-center uppercase shadow-2xl">
                            Lihat Detail
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full py-20 text-center bg-gray-50 rounded-[3rem] border-2 border-dashed border-gray-100">
                <p class="text-gray-400 text-xs font-black uppercase italic tracking-widest">Buku tidak ditemukan.</p>
            </div>
            @endforelse
        </div>
    </section>
</div>
@endsection