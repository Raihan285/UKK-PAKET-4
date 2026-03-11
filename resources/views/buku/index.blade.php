@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div x-data="{ 
    openModal: false, 
    openEditModal: false, 
    editData: {id: '', judul: '', penulis: '', kategori: '', stok: ''} 
}" class="max-w-7xl mx-auto space-y-8 py-6 px-4 animate-in fade-in duration-500">
    
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter uppercase">Manajemen Koleksi</h2>
            <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-[0.3em] mt-2 border-l-4 border-blue-600 pl-4">
                Pusat Kontrol Database Perpustakaan
            </p>
        </div>
        <button @click="openModal = true" class="bg-gray-900 dark:bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all shadow-2xl active:scale-95 flex items-center gap-3">
            <i class="ph-bold ph-plus text-lg"></i> 
            Tambah Buku
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-800">
                        <th class="px-10 py-6 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 dark:text-slate-500">Visual</th>
                        <th class="px-6 py-6 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 dark:text-slate-500">Informasi Buku</th>
                        <th class="px-6 py-6 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 dark:text-slate-500">Klasifikasi</th>
                        <th class="px-6 py-6 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 dark:text-slate-500 text-center">Persediaan</th>
                        <th class="px-10 py-6 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 dark:text-slate-500 text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-800">
                    @forelse($buku as $item)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-blue-900/5 transition-colors group">
                        <td class="px-10 py-6">
                            <div class="relative w-16 h-24 overflow-hidden rounded-2xl shadow-lg group-hover:scale-105 transition-transform duration-500">
                                <img src="{{ asset('storage/' . $item->cover) }}" class="w-full h-full object-cover">
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex flex-col space-y-1">
                                <span class="font-black text-gray-900 dark:text-white text-lg tracking-tight leading-tight uppercase group-hover:text-blue-600 transition-colors">{{ $item->judul }}</span>
                                <span class="text-gray-400 dark:text-slate-500 text-[11px] font-bold uppercase tracking-wider italic">
                                    {{ $item->penulis }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <span class="px-4 py-1.5 bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-400 rounded-lg text-[9px] font-black uppercase tracking-widest border border-gray-200 dark:border-slate-700">
                                {{ $item->kategori }}
                            </span>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <div class="inline-flex flex-col items-center min-w-[60px] p-2 bg-blue-50/50 dark:bg-slate-800 rounded-xl border border-blue-100 dark:border-slate-700">
                                <span class="text-lg font-black text-blue-600 dark:text-blue-400">{{ $item->stok }}</span>
                                <span class="text-[8px] text-gray-400 uppercase font-black tracking-tighter">Unit</span>
                            </div>
                        </td>
                        <td class="px-10 py-6">
                            <div class="flex justify-end gap-3">
                                <button @click="editData = { id: '{{ $item->id }}', judul: '{{ addslashes($item->judul) }}', penulis: '{{ addslashes($item->penulis) }}', kategori: '{{ $item->kategori }}', stok: '{{ $item->stok }}' }; openEditModal = true;" 
                                    class="w-11 h-11 flex items-center justify-center bg-white dark:bg-slate-800 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-gray-100 dark:border-slate-700">
                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                </button>
                                
                                <form action="{{ route('buku.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus literatur ini?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="w-11 h-11 flex items-center justify-center bg-white dark:bg-slate-800 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm border border-gray-100 dark:border-slate-700">
                                        <i class="ph-bold ph-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-32 text-center">
                            <p class="text-gray-300 dark:text-slate-700 text-[11px] font-black uppercase tracking-[0.5em]">Database Kosong</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal --}}
    <template x-teleport="body">
        <div>
            {{-- Modal Create --}}
            <div x-show="openModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div @click="openModal = false" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
                <div class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-[3rem] p-12 shadow-2xl relative z-10 border dark:border-slate-800">
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-8 uppercase tracking-tighter">Tambah Literatur</h3>
                    <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <input type="text" name="judul" placeholder="JUDUL BUKU" class="w-full px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none focus:ring-2 focus:ring-blue-600 border-none font-bold uppercase placeholder:text-gray-300" required>
                        <div class="grid grid-cols-2 gap-6">
                            <input type="text" name="penulis" placeholder="PENULIS" class="px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none border-none font-bold uppercase placeholder:text-gray-300" required>
                            <select name="kategori" class="px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none border-none font-bold uppercase" required>
                                <option value="">KATEGORI</option>
                                @foreach($categories as $cat) <option value="{{ $cat }}">{{ $cat }}</option> @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-6 items-center">
                            <input type="number" name="stok" placeholder="STOK" class="px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none border-none font-bold" required>
                            <input type="file" name="cover" class="text-[10px] font-black uppercase text-gray-400 file:bg-blue-600 file:text-white file:border-none file:px-4 file:py-2 file:rounded-lg file:mr-4 cursor-pointer" required>
                        </div>
                        <div class="flex gap-4 pt-6">
                            <button type="submit" class="flex-1 bg-blue-600 text-white font-black py-5 rounded-2xl hover:bg-blue-700 transition-all uppercase tracking-widest text-xs">Simpan Data</button>
                            <button type="button" @click="openModal = false" class="px-10 py-5 bg-gray-100 dark:bg-slate-800 text-gray-400 font-black rounded-2xl hover:bg-gray-200 transition-all uppercase tracking-widest text-xs">Batal</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Modal Edit --}}
            <div x-show="openEditModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div @click="openEditModal = false" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
                <div class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-[3rem] p-12 shadow-2xl relative z-10 border dark:border-slate-800">
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-8 uppercase tracking-tighter">Edit Literatur</h3>
                    <form :action="'/buku/' + editData.id" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf @method('PUT')
                        <input type="text" name="judul" x-model="editData.judul" class="w-full px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none border-none font-bold uppercase focus:ring-2 focus:ring-blue-600" required>
                        <div class="grid grid-cols-2 gap-6">
                            <input type="text" name="penulis" x-model="editData.penulis" class="px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none border-none font-bold uppercase" required>
                            <select name="kategori" x-model="editData.kategori" class="px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none border-none font-bold uppercase" required>
                                @foreach($categories as $cat) <option value="{{ $cat }}">{{ $cat }}</option> @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-6 items-center">
                            <input type="number" name="stok" x-model="editData.stok" class="px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none border-none font-bold" required>
                            <input type="file" name="cover" class="text-[10px] font-black uppercase text-gray-400 file:bg-blue-600 file:text-white file:border-none file:px-4 file:py-2 file:rounded-lg file:mr-4">
                        </div>
                        <div class="flex gap-4 pt-6">
                            <button type="submit" class="flex-1 bg-blue-600 text-white font-black py-5 rounded-2xl hover:bg-blue-700 transition-all uppercase tracking-widest text-xs">Update Koleksi</button>
                            <button type="button" @click="openEditModal = false" class="px-10 py-5 bg-gray-100 dark:bg-slate-800 text-gray-400 font-black rounded-2xl hover:bg-gray-200 transition-all uppercase tracking-widest text-xs">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection