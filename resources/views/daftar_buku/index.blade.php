@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-12 py-8 px-4 transition-colors duration-500">
    
    {{--Header Dan Filter Kategori --}}
    <div class="space-y-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left">
                <h2 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter uppercase italic">
                    Library <span class="text-blue-600">Collection</span>
                </h2>
                <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-[0.3em] mt-2 border-l-0 md:border-l-4 border-blue-600 md:pl-4">
                    @if($kategori_dipilih) 
                        Menampilkan Kategori: <span class="text-blue-600">{{ $kategori_dipilih }}</span>
                    @else 
                        Eksplorasi ribuan literatur digital dan fisik.
                    @endif
                </p>
            </div>

            <div class="flex flex-wrap gap-2 justify-center">
                <a href="{{ route('daftar_buku.index') }}" 
                   class="px-5 py-2.5 rounded-xl text-[10px] font-black transition-all uppercase tracking-widest {{ !$kategori_dipilih ? 'bg-blue-600 text-white shadow-xl shadow-blue-500/20' : 'bg-gray-100 dark:bg-slate-800 text-gray-400 hover:bg-gray-200' }}">
                    Semua Koleksi
                </a>
                @php $kategoris = array_filter($setting->daftar_kategori ?? []); @endphp
                @foreach($kategoris as $kat)
                    <a href="{{ route('daftar_buku.index', ['kategori' => $kat]) }}" 
                       class="px-5 py-2.5 rounded-xl text-[10px] font-black transition-all uppercase tracking-widest {{ $kategori_dipilih == $kat ? 'bg-blue-600 text-white shadow-xl shadow-blue-500/20' : 'bg-white dark:bg-slate-900 text-gray-400 border border-gray-100 dark:border-slate-800 hover:bg-gray-50' }}">
                        {{ $kat }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Buku Rekomendasi --}}
    @if(!$kategori_dipilih && $books->where('is_recommended', true)->count() > 0)
    <section class="space-y-6">
        <div class="flex items-center gap-4">
            <h3 class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-[0.3em]">Editor's Choice</h3>
            <div class="h-[1px] flex-1 bg-gradient-to-r from-gray-200 dark:from-slate-800 to-transparent"></div>
        </div>

        {{-- Grid Yang Dapat Menyesuaikan Ukuran Layar --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
            @foreach($books->where('is_recommended', true) as $book)
            <a href="{{ route('daftar_buku.show', $book->id) }}" class="group relative aspect-[3/4.5] overflow-hidden rounded-[2.5rem] bg-gray-100 dark:bg-slate-800 block shadow-2xl transition-all duration-500 hover:-translate-y-3">
                <img src="{{ asset('storage/' . $book->cover) }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-70 group-hover:opacity-90 transition-opacity"></div>

                <div class="absolute inset-0 p-8 flex flex-col justify-end">
                    <div class="mb-3">
                        <span class="px-3 py-1 bg-amber-400 text-white text-[8px] font-black rounded-full uppercase tracking-widest shadow-lg">FEATURED</span>
                    </div>
                    <h4 class="text-white font-black text-lg leading-tight line-clamp-2 uppercase italic tracking-tighter group-hover:text-blue-400 transition-colors">
                        {{ $book->judul }}
                    </h4>
                    <div class="h-0.5 w-0 group-hover:w-full bg-blue-500 transition-all duration-500 mt-2"></div>
                    <p class="text-gray-300 text-[10px] mt-3 font-bold uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-all transform translate-y-2 group-hover:translate-y-0">
                        {{ $book->penulis }}
                    </p>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{--Daftar Buku Lainnya --}}
    <section class="space-y-6">
        <div class="flex items-center gap-4">
            <h3 class="text-xs font-black text-gray-400 dark:text-slate-600 uppercase tracking-[0.3em]">
                {{ $kategori_dipilih ? 'Hasil Klasifikasi' : 'Koleksi Umum' }}
            </h3>
            <div class="h-[1px] flex-1 bg-gradient-to-r from-gray-100 dark:from-slate-900 to-transparent"></div>
        </div> 

        {{-- Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6">
            @forelse($books->where('is_recommended', false) as $book)
            <a href="{{ route('daftar_buku.show', $book->id) }}" class="group relative aspect-[3/4.5] overflow-hidden rounded-[2rem] bg-white dark:bg-slate-900 block border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-xl hover:-translate-y-1">
                <img src="{{ asset('storage/' . $book->cover) }}" class="absolute inset-0 w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-all">
                
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>

                <div class="absolute inset-0 p-5 flex flex-col justify-end">
                    <h4 class="text-white font-black text-xs leading-tight line-clamp-2 uppercase tracking-tighter">
                        {{ $book->judul }}
                    </h4>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-[8px] text-blue-400 font-black uppercase">{{ $book->kategori }}</span>
                        <span class="text-[8px] text-gray-400 font-bold uppercase">{{ $book->stok }} Unit</span>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full py-24 text-center bg-gray-50/50 dark:bg-slate-900/50 rounded-[3rem] border-2 border-dashed border-gray-200 dark:border-slate-800">
                <i class="ph-bold ph-books text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.4em]">Koleksi Belum Tersedia</p>
            </div>
            @endforelse
        </div>
    </section>
</div>
@endsection