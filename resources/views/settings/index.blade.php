@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-8 bg-gray-50/50 dark:bg-slate-950 min-h-screen transition-colors duration-500">
    {{-- Header --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight italic uppercase">System <span class="text-blue-600">Settings</span></h2>
            <p class="text-gray-500 dark:text-slate-400 mt-1 font-medium italic">Konfigurasi parameter operasional perpustakaan.</p>
        </div>
        <div class="text-[10px] font-black text-gray-400 dark:text-slate-600 uppercase tracking-[0.3em] bg-white dark:bg-slate-900 px-4 py-2 rounded-full border border-gray-100 dark:border-slate-800 shadow-sm">
            Last Updated: {{ now()->format('d M Y') }}
        </div>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="mb-8 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 p-4 rounded-2xl font-bold text-sm border border-emerald-100 dark:border-emerald-800 flex items-center shadow-lg shadow-emerald-500/5 animate-in fade-in slide-in-from-top-4 duration-500">
            <i class="ph-bold ph-check-circle mr-3 text-xl"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        {{-- Card Denda & Waktu --}}
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-slate-800 shadow-xl shadow-gray-200/50 dark:shadow-none transition-all hover:shadow-2xl group">
            <div class="flex items-center space-x-4 mb-8">
                <div class="p-4 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl shadow-inner group-hover:rotate-6 transition-transform">
                    <i class="ph-bold ph-calendar-check text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-black text-gray-800 dark:text-white text-lg">Denda & Waktu Pinjam</h3>
                    <p class="text-gray-400 dark:text-slate-500 text-xs font-bold uppercase tracking-widest">Atur Parameter Dasar</p>
                </div>
            </div>
            
            <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
                @csrf
                <div class="group/input">
                    <label class="block text-[11px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-2 px-1">Batas Waktu Pinjam (Hari)</label>
                    <div class="relative">
                        <input type="number" name="batas_hari" value="{{ $setting->batas_hari }}" class="w-full pl-5 pr-12 py-4 rounded-2xl bg-gray-50 dark:bg-slate-800 border-2 border-transparent dark:text-white focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 focus:ring-0 transition-all font-bold text-gray-700 shadow-sm">
                        <span class="absolute right-5 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400 uppercase">Hari</span>
                    </div>
                </div>
                <div class="group/input">
                    <label class="block text-[11px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-2 px-1">Denda Per Hari (Rp)</label>
                    <div class="relative">
                        <input type="number" name="denda_per_hari" value="{{ $setting->denda_per_hari }}" class="w-full pl-12 pr-5 py-4 rounded-2xl bg-gray-50 dark:bg-slate-800 border-2 border-transparent dark:text-white focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 focus:ring-0 transition-all font-bold text-gray-700 shadow-sm">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400 uppercase">Rp</span>
                    </div>
                </div>
                <button type="submit" class="w-full bg-slate-900 dark:bg-blue-600 text-white py-5 rounded-2xl font-black shadow-lg shadow-blue-200 dark:shadow-none hover:bg-blue-700 hover:-translate-y-1 transition-all active:scale-[0.98] uppercase tracking-widest text-xs">
                   Perbarui Pengaturan
                </button>
            </form>
        </div>

        {{-- Card Kategori Buku --}}
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-slate-800 shadow-xl shadow-gray-200/50 dark:shadow-none flex flex-col transition-all hover:shadow-2xl group">
            <div class="flex items-center space-x-4 mb-8">
                <div class="p-4 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-2xl shadow-inner group-hover:-rotate-6 transition-transform">
                    <i class="ph-bold ph-tag text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-black text-gray-800 dark:text-white text-lg">Klasifikasi Kategori</h3>
                    <p class="text-gray-400 dark:text-slate-500 text-xs font-bold uppercase tracking-widest">Manajemen Label Buku</p>
                </div>
            </div>

            <form action="{{ route('settings.category.store') }}" method="POST" class="flex space-x-3 mb-8">
                @csrf
                <input type="text" name="nama_kategori" required placeholder="Nama kategori baru..." class="flex-1 px-5 py-4 rounded-2xl bg-gray-50 dark:bg-slate-800 border-2 border-transparent dark:text-white focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-800 transition-all font-bold text-gray-700 shadow-sm italic">
                <button type="submit" class="bg-indigo-600 text-white px-8 rounded-2xl font-black hover:bg-indigo-700 transition-all active:scale-95 shadow-lg shadow-indigo-100 dark:shadow-none">
                    <i class="ph-bold ph-plus"></i>
                </button>
            </form>

            <div class="flex flex-wrap gap-2 overflow-y-auto max-h-[220px] p-2 bg-gray-50/50 dark:bg-slate-800/30 rounded-3xl border border-gray-100/50 dark:border-slate-800/50">
                @php 
                    $kategoris = array_filter($setting->daftar_kategori ?? []); 
                    sort($kategoris);
                @endphp

                @forelse($kategoris as $k)
                    <div class="inline-flex items-center bg-white dark:bg-slate-800 hover:border-indigo-400 dark:hover:border-indigo-500 border border-gray-200 dark:border-slate-700 px-4 py-2 rounded-xl transition-all group/tag shadow-sm">
                        <span class="text-[11px] font-black text-gray-700 dark:text-slate-200 mr-3 uppercase tracking-tighter">{{ $k }}</span>
                        <form action="{{ route('settings.category.destroy', ['category' => $k]) }}" method="POST" class="leading-none flex items-center">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus kategori {{ $k }}?')" class="text-gray-300 dark:text-slate-600 hover:text-red-500 transition-colors">
                                <i class="ph-bold ph-trash text-sm"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="w-full py-12 flex flex-col items-center justify-center opacity-40">
                        <i class="ph-bold ph-folder-open text-4xl mb-2"></i>
                        <p class="font-bold text-xs uppercase tracking-widest">Kosong</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Card Rekomendasi Buku --}}
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-slate-800 shadow-xl shadow-gray-200/50 dark:shadow-none">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div class="flex items-center space-x-4">
                    <div class="p-4 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-2xl shadow-inner">
                        <i class="ph-bold ph-crown text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-gray-800 dark:text-white text-lg">Buku Rekomendasi</h3>
                        <p class="text-gray-400 dark:text-slate-500 text-xs font-bold uppercase tracking-widest">Tandai Koleksi Unggulan</p>
                    </div>
                </div>
                <div class="relative">
                    <input type="text" id="searchBook" placeholder="Cari buku..." class="pl-10 pr-4 py-2 rounded-xl bg-gray-50 dark:bg-slate-800 border border-transparent focus:border-amber-400 text-xs font-bold transition-all w-full md:w-64">
                    <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <div class="overflow-x-auto max-h-[500px] overflow-y-auto no-scrollbar rounded-3xl border border-gray-50 dark:border-slate-800">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-white dark:bg-slate-900 z-10 shadow-sm">
                        <tr class="text-[10px] font-black text-gray-400 dark:text-slate-600 uppercase tracking-[0.2em]">
                            <th class="py-4 px-6 border-b border-gray-50 dark:border-slate-800">Buku & Klasifikasi</th>
                            <th class="py-4 px-6 border-b border-gray-50 dark:border-slate-800">Penulis</th>
                            <th class="py-4 px-6 border-b border-gray-50 dark:border-slate-800 text-right">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-slate-800" id="bookTable">
                        @foreach($books as $book)
                        <tr class="group hover:bg-blue-50/30 dark:hover:bg-blue-900/5 transition-all">
                            <td class="py-5 px-6">
                                <div class="flex flex-col">
                                    <span class="font-black text-gray-800 dark:text-slate-200 text-sm tracking-tighter uppercase italic">{{ $book->judul }}</span>
                                    <div class="flex items-center mt-1">
                                        <span class="text-[9px] px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500 dark:text-indigo-400 font-black uppercase rounded-md border border-indigo-100 dark:border-indigo-800">{{ $book->kategori ?? 'Umum' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5 px-6 text-gray-500 dark:text-slate-400 font-bold text-xs italic">{{ $book->penulis }}</td>
                            <td class="py-5 px-6 text-right">
                                <form action="{{ route('settings.recommendation.toggle', $book->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[9px] font-black tracking-widest transition-all {{ $book->is_recommended ? 'bg-amber-500 text-white shadow-lg shadow-amber-200 dark:shadow-none' : 'bg-gray-100 text-gray-400 hover:bg-gray-200 dark:bg-slate-800 dark:text-slate-500 dark:hover:bg-slate-700' }}">
                                        <i class="ph-bold {{ $book->is_recommended ? 'ph-star-fill' : 'ph-star' }}"></i>
                                        {{ $book->is_recommended ? 'FEATURED' : 'MARK' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Logika Filter
    document.getElementById('searchBook').addEventListener('keyup', function() {
        let val = this.value.toLowerCase();
        let rows = document.querySelectorAll('#bookTable tr');
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(val) ? '' : 'none';
        });
    });
</script>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection