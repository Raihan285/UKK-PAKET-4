@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div x-data="{ 
    openModal: false, 
    openEditModal: false, 
    editData: {id: '', judul: '', penulis: '', kategori: '', stok: ''} 
}" class="max-w-7xl mx-auto space-y-8 animate-in fade-in duration-500 transition-colors">
    
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Koleksi Buku</h2>
            <p class="text-gray-500 dark:text-slate-400 text-sm mt-1">Pusat kontrol buku perpustakaan.</p>
        </div>
        <button @click="openModal = true" class="group bg-gray-900 dark:bg-blue-600 hover:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold text-sm flex items-center transition-all duration-300 shadow-xl shadow-gray-200 dark:shadow-none active:scale-95">
            <i class="ph-bold ph-plus mr-2 group-hover:rotate-90 transition-transform duration-300"></i> 
            Tambah Koleksi Baru
        </button>
    </div>

    {{-- TABLE CARD --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-800">
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 dark:text-slate-500">Cover</th>
                        <th class="px-6 py-5 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 dark:text-slate-500">Detail</th>
                        <th class="px-6 py-5 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 dark:text-slate-500">Kategori</th>
                        <th class="px-6 py-5 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 dark:text-slate-500">Stok</th>
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 dark:text-slate-500 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-800 text-sm">
                    @forelse($buku as $item)
                    <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors group">
                        <td class="px-8 py-4">
                            <div class="relative w-14 h-20 overflow-hidden rounded-xl shadow-md border dark:border-slate-700">
                                <img src="{{ asset('storage/' . $item->cover) }}" class="w-full h-full object-cover">
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-black text-gray-900 dark:text-white text-base mb-0.5">{{ $item->judul }}</span>
                                <span class="text-gray-400 dark:text-slate-500 flex items-center text-xs italic">
                                    <i class="ph-bold ph-user-circle mr-1 text-blue-400 text-sm"></i> {{ $item->penulis }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-500 dark:text-slate-400">
                                {{ $item->kategori }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-slate-800 flex flex-col items-center justify-center border border-gray-100 dark:border-slate-700">
                                    <span class="text-xs font-black text-gray-900 dark:text-white">{{ $item->stok }}</span>
                                    <span class="text-[8px] text-gray-400 uppercase font-bold tracking-tighter">Pcs</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <div class="flex justify-end items-center gap-3">
                                <button type="button" 
                                    @click="
                                        editData = { 
                                            id: '{{ $item->id }}', 
                                            judul: '{{ addslashes($item->judul) }}', 
                                            penulis: '{{ addslashes($item->penulis) }}', 
                                            kategori: '{{ $item->kategori }}', 
                                            stok: '{{ $item->stok }}' 
                                        }; 
                                        openEditModal = true;
                                    " 
                                    class="w-10 h-10 flex items-center justify-center bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-xl hover:bg-blue-600 dark:hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100 dark:border-blue-900/30">
                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                </button>
                                
                                <form action="{{ route('buku.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus koleksi ini?')" class="inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 flex items-center justify-center bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-xl hover:bg-red-600 dark:hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-100 dark:border-red-900/30">
                                        <i class="ph-bold ph-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center text-gray-400 dark:text-slate-600 font-bold uppercase tracking-widest text-xs">Rak buku masih kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODALS --}}
    <template x-teleport="body">
        <div>
            {{-- MODAL CREATE --}}
            <div x-show="openModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div @click="openModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 bg-gray-900/60 dark:bg-slate-950/80 backdrop-blur-md"></div>
                <div class="bg-white dark:bg-slate-900 w-full max-w-xl rounded-[3rem] p-10 shadow-2xl relative z-10 border dark:border-slate-800"
                     x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-6">Tambah Koleksi</h3>
                    <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <input type="text" name="judul" placeholder="Judul Buku" class="w-full px-5 py-4 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-[1.2rem] outline-none focus:ring-2 focus:ring-blue-500 border dark:border-slate-700 transition-all" required>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="penulis" placeholder="Penulis" class="px-5 py-4 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-[1.2rem] outline-none border dark:border-slate-700" required>
                            <select name="kategori" class="px-5 py-4 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-[1.2rem] outline-none border dark:border-slate-700" required>
                                <option value="" class="dark:bg-slate-900">Pilih Kategori</option>
                                @foreach($categories as $cat) <option value="{{ $cat }}" class="dark:bg-slate-900">{{ $cat }}</option> @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="number" name="stok" placeholder="Stok" class="px-5 py-4 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-[1.2rem] outline-none border dark:border-slate-700" required>
                            <div class="flex items-center px-2">
                                <input type="file" name="cover" class="text-[10px] text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 dark:file:bg-blue-900/30 file:text-blue-700 dark:file:text-blue-400 font-bold" required>
                            </div>
                        </div>
                        <div class="flex gap-4 pt-4">
                            <button type="submit" class="flex-1 bg-gray-900 dark:bg-blue-600 text-white font-black py-4 rounded-[1.5rem] hover:bg-blue-600 transition-colors">Simpan</button>
                            <button type="button" @click="openModal = false" class="px-8 py-4 bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-500 font-black rounded-[1.5rem] hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors">Batal</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- MODAL EDIT --}}
            <div x-show="openEditModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div @click="openEditModal = false" class="absolute inset-0 bg-gray-900/60 dark:bg-slate-950/80 backdrop-blur-md"></div>
                <div class="bg-white dark:bg-slate-900 w-full max-w-xl rounded-[3rem] p-10 shadow-2xl relative z-10 border dark:border-slate-800"
                     x-show="openEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-6">Edit Literatur</h3>
                    <form :action="'/buku/' + editData.id" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf @method('PUT')
                        <input type="text" name="judul" x-model="editData.judul" class="w-full px-5 py-4 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-[1.2rem] outline-none border dark:border-slate-700 focus:ring-2 focus:ring-blue-500" required>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="penulis" x-model="editData.penulis" class="px-5 py-4 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-[1.2rem] outline-none border dark:border-slate-700" required>
                            <select name="kategori" x-model="editData.kategori" class="px-5 py-4 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-[1.2rem] outline-none border dark:border-slate-700" required>
                                @foreach($categories as $cat) <option value="{{ $cat }}" class="dark:bg-slate-900">{{ $cat }}</option> @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="number" name="stok" x-model="editData.stok" class="px-5 py-4 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-[1.2rem] outline-none border dark:border-slate-700" required>
                            <div class="flex items-center px-2">
                                <input type="file" name="cover" class="text-[10px] text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 dark:file:bg-blue-900/30 file:text-blue-700 dark:file:text-blue-400 font-bold">
                            </div>
                        </div>
                        <div class="flex gap-4 pt-4">
                            <button type="submit" class="flex-1 bg-blue-600 text-white font-black py-4 rounded-[1.5rem] hover:bg-blue-700 transition-colors">Update</button>
                            <button type="button" @click="openEditModal = false" class="px-8 py-4 bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-500 font-black rounded-[1.5rem] hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection