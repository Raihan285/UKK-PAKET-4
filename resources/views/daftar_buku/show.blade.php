@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8 transition-colors duration-500">
    {{-- Back Button --}}
    <a href="{{ route('daftar_buku.index') }}" class="inline-flex items-center gap-3 text-gray-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 mb-10 transition-all group">
        <div class="w-8 h-8 rounded-full border border-gray-100 dark:border-slate-800 flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20 group-hover:border-blue-100 dark:group-hover:border-blue-800">
            <i class="ph ph-arrow-left font-bold transition-transform group-hover:-translate-x-1"></i>
        </div>
        <span class="text-[10px] font-black uppercase tracking-[0.2em]">Kembali ke Perpustakaan</span>
    </a>

    <div class="flex flex-col lg:flex-row gap-16 items-start">
        
        {{-- LEFT COLUMN: Visual Cover --}}
        <div class="w-full lg:w-[400px] shrink-0 lg:sticky lg:top-8">
            <div class="relative group">
                <div class="absolute -inset-4 bg-blue-500/10 dark:bg-blue-500/5 blur-3xl rounded-[3rem] opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                
                <div class="relative aspect-[3/4.5] rounded-[3rem] overflow-hidden shadow-[0_30px_60px_-15px_rgba(0,0,0,0.3)] dark:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.6)] transform lg:-rotate-2 hover:rotate-0 transition-all duration-700 bg-gray-100 dark:bg-slate-800">
                    <img src="{{ asset('storage/' . $book->cover) }}" class="w-full h-full object-cover">
                    
                    @if($book->is_recommended)
                    <div class="absolute top-6 left-6">
                        <span class="px-4 py-2 bg-amber-400 text-white text-[10px] font-black rounded-xl shadow-lg uppercase tracking-widest">
                            ★ Pilihan Redaksi
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Information & Action --}}
        <div class="flex-1 space-y-10 py-4">
            {{-- Header Info --}}
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 bg-blue-600 text-white rounded-lg text-[9px] font-black uppercase tracking-widest">{{ $book->kategori }}</span>
                    <div class="h-[1px] w-12 bg-gray-200 dark:bg-slate-800"></div>
                    <span class="text-[9px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest">ID: #B-{{ str_pad($book->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                
                <h1 class="text-5xl font-black text-gray-900 dark:text-white leading-[1.1] tracking-tighter uppercase">{{ $book->judul }}</h1>
                
                <div class="flex items-center gap-4 text-gray-500 dark:text-slate-400">
                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 border-2 border-white dark:border-slate-800 flex items-center justify-center text-[10px] font-bold text-blue-600 dark:text-blue-400">
                        {{ substr($book->penulis, 0, 1) }}
                    </div>
                    <p class="text-sm font-bold tracking-tight">Karya <span class="text-blue-600 dark:text-blue-400">{{ $book->penulis }}</span></p>
                </div>
            </div>

            {{-- Metadata Grid --}}
            <div class="grid grid-cols-2 gap-6 p-8 bg-gray-50 dark:bg-slate-900/50 rounded-[2.5rem] border border-gray-100 dark:border-slate-800">
                <div>
                    <p class="text-[9px] text-gray-400 dark:text-slate-500 font-black uppercase tracking-widest mb-1">Ketersediaan Stok</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ $book->stok }} <span class="text-[10px] text-gray-400 font-bold"></span></p>
                </div>
                <div>
                    <p class="text-[9px] text-gray-400 dark:text-slate-500 font-black uppercase tracking-widest mb-1">Status Peminjaman</p>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="w-2.5 h-2.5 rounded-full {{ $book->stok > 0 ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}"></div>
                        <p class="text-xs font-black {{ $book->stok > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }} uppercase tracking-wider transition-colors">
                            {{ $book->stok > 0 ? 'Tersedia Untuk Dipinjam' : 'Sedang Kosong' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Synopsis/Description --}}
            <div class="space-y-4">
                <h3 class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-[0.2em]">Tentang Buku</h3>
                <p class="text-gray-500 dark:text-slate-400 text-sm leading-relaxed font-medium">
                    {{ $book->deskripsi ?? 'Jelajahi karya luar biasa ini yang menyajikan perspektif mendalam dalam kategori ' . $book->kategori . '. Buku ini dirancang untuk memberikan wawasan berharga bagi pembaca.' }}
                </p>
            </div>

            {{-- CTA Action --}}
            <div class="pt-6">
                @if($book->stok > 0)
                <form action="{{ route('transaksi.store') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-4">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    <button type="submit" class="group w-full sm:w-auto px-12 py-5 bg-gray-900 dark:bg-blue-600 text-white rounded-[1.5rem] font-black text-[11px] uppercase tracking-widest hover:bg-blue-600 dark:hover:bg-blue-700 hover:shadow-[0_20px_40px_-10px_rgba(37,99,235,0.4)] transition-all duration-500 flex items-center justify-center gap-4">
                        <i class="ph-fill ph-hand-pointing text-xl group-hover:scale-125 transition-transform"></i>
                        Ajukan Pinjam Sekarang
                    </button>
                    
                    <button type="button" class="w-full sm:w-auto p-5 border border-gray-200 dark:border-slate-800 rounded-[1.5rem] hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors text-gray-400 dark:text-slate-500">
                        <i class="ph ph-heart text-xl"></i>
                    </button>
                </form>
                @else
                <div class="p-8 border-2 border-dashed border-gray-100 dark:border-slate-800 rounded-[2.5rem] text-center bg-gray-50/50 dark:bg-slate-900/30">
                    <p class="text-gray-400 dark:text-slate-600 text-[10px] font-black uppercase tracking-widest italic">Maaf, unit sedang tidak tersedia di rak kami.</p>
                </div>
                @endif
                
                {{-- Info Note --}}
                <div class="mt-8 p-6 bg-blue-50/50 dark:bg-blue-900/10 rounded-2xl flex items-start gap-4 border border-blue-50 dark:border-blue-900/20">
                    <i class="ph ph-info text-xl text-blue-500"></i>
                    <p class="text-[10px] text-gray-500 dark:text-slate-400 font-bold uppercase tracking-tight leading-relaxed">
                        Catatan: <span class="text-gray-400 dark:text-slate-500 font-medium lowercase italic">Setelah mengajukan pinjaman secara online, harap tunggu konfirmasi admin sebelum mengambil buku.</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection