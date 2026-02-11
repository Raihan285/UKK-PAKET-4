@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    {{-- Back Button --}}
    <a href="{{ route('daftar_buku.index') }}" class="inline-flex items-center gap-3 text-gray-400 hover:text-blue-600 mb-10 transition-all group">
        <div class="w-8 h-8 rounded-full border border-gray-100 flex items-center justify-center group-hover:bg-blue-50 group-hover:border-blue-100">
            <i class="ph ph-arrow-left font-bold transition-transform group-hover:-translate-x-1"></i>
        </div>
        <span class="text-[10px] font-black uppercase tracking-[0.2em]">Kembali ke Perpustakaan</span>
    </a>

    <div class="flex flex-col lg:flex-row gap-16 items-start">
        
        {{-- LEFT COLUMN: Visual Cover --}}
        <div class="w-full lg:w-[400px] shrink-0 lg:sticky lg:top-8">
            <div class="relative group">
                <div class="absolute -inset-4 bg-blue-500/10 blur-3xl rounded-[3rem] opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                
                <div class="relative aspect-[3/4.5] rounded-[3rem] overflow-hidden shadow-[0_30px_60px_-15px_rgba(0,0,0,0.3)] transform lg:-rotate-2 hover:rotate-0 transition-all duration-700 bg-gray-100">
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
                    <div class="h-[1px] w-12 bg-gray-200"></div>
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">ID: #B-{{ str_pad($book->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                
                <h1 class="text-5xl font-black text-gray-900 leading-[1.1] tracking-tighter uppercase">{{ $book->judul }}</h1>
                
                <div class="flex items-center gap-4 text-gray-500">
                    <div class="w-8 h-8 rounded-full bg-blue-100 border-2 border-white flex items-center justify-center text-[10px] font-bold text-blue-600">
                        {{ substr($book->penulis, 0, 1) }}
                    </div>
                    <p class="text-sm font-bold tracking-tight">Karya <span class="text-blue-600">{{ $book->penulis }}</span></p>
                </div>
            </div>

            {{-- Metadata Grid (Diperbarui jadi 2 Kolom) --}}
            <div class="grid grid-cols-2 gap-6 p-8 bg-gray-50 rounded-[2.5rem] border border-gray-100">
                <div>
                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest mb-1">Ketersediaan Stok</p>
                    <p class="text-xl font-black text-gray-900">{{ $book->stok }} <span class="text-[10px] text-gray-400 font-bold"></span></p>
                </div>
                <div>
                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest mb-1">Status Peminjaman</p>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="w-2.5 h-2.5 rounded-full {{ $book->stok > 0 ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}"></div>
                        <p class="text-xs font-black {{ $book->stok > 0 ? 'text-emerald-600' : 'text-red-600' }} uppercase tracking-wider">
                            {{ $book->stok > 0 ? 'Tersedia Untuk Dipinjam' : 'Sedang Kosong' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Synopsis/Description --}}
            <div class="space-y-4">
                <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-[0.2em]">Tentang Buku</h3>
                <p class="text-gray-500 text-sm leading-relaxed font-medium">
                    {{ $book->deskripsi ?? 'Jelajahi karya luar biasa ini yang menyajikan perspektif mendalam dalam kategori ' . $book->kategori . '. Buku ini dirancang untuk memberikan wawasan berharga bagi pembaca yang ingin memperluas cakrawala pengetahuan mereka melalui narasi yang kuat dan informatif.' }}
                </p>
            </div>

            {{-- CTA Action --}}
            <div class="pt-6">
                @if($book->stok > 0)
                <form action="{{ route('transaksi.store') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-4">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    <button type="submit" class="group w-full sm:w-auto px-12 py-5 bg-gray-900 text-white rounded-[1.5rem] font-black text-[11px] uppercase tracking-widest hover:bg-blue-600 hover:shadow-[0_20px_40px_-10px_rgba(37,99,235,0.4)] transition-all duration-500 flex items-center justify-center gap-4">
                        <i class="ph-fill ph-hand-pointing text-xl group-hover:scale-125 transition-transform"></i>
                        Ajukan Pinjam Sekarang
                    </button>
                    
                    <button type="button" class="w-full sm:w-auto p-5 border border-gray-200 rounded-[1.5rem] hover:bg-gray-50 transition-colors text-gray-400">
                        <i class="ph ph-heart text-xl"></i>
                    </button>
                </form>
                @else
                <div class="p-8 border-2 border-dashed border-gray-100 rounded-[2.5rem] text-center bg-gray-50/50">
                    <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest italic">Maaf, unit sedang tidak tersedia di rak kami.</p>
                </div>
                @endif
                
                <div class="mt-8 p-6 bg-blue-50/50 rounded-2xl flex items-start gap-4">
                    <i class="ph ph-info text-xl text-blue-500"></i>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tight leading-relaxed">
                        Catatan: <span class="text-gray-400 font-medium lowercase italic">Setelah mengajukan pinjaman secara online, harap tunggu konfirmasi admin di menu riwayat sebelum mengambil buku di meja pustakawan.</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection