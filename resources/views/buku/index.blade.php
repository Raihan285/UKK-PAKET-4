@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div x-data="{ 
    openModal: false, 
    openEditModal: false, 
    editData: {id: '', judul: '', penulis: '', kategori: '', stok: ''} 
}" class="max-w-7xl mx-auto space-y-8 animate-in fade-in duration-500">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Koleksi Buku</h2>
            <p class="text-gray-500 text-sm mt-1">Pusat kontrol buku perpustakaan.</p>
        </div>
        <button @click="openModal = true" class="group bg-gray-900 hover:bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold text-sm flex items-center transition-all duration-300 shadow-xl shadow-gray-200 active:scale-95">
            <i class="ph-bold ph-plus mr-2 group-hover:rotate-90 transition-transform duration-300"></i> 
            Tambah Koleksi Baru
        </button>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Cover</th>
                        <th class="px-6 py-5 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Detail</th>
                        <th class="px-6 py-5 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Kategori</th>
                        <th class="px-6 py-5 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Stok</th>
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($buku as $item)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-8 py-4">
                            <div class="relative w-14 h-20 overflow-hidden rounded-xl shadow-md">
                                <img src="{{ asset('storage/' . $item->cover) }}" class="w-full h-full object-cover">
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-black text-gray-900 text-base mb-0.5">{{ $item->judul }}</span>
                                <span class="text-gray-400 flex items-center text-xs italic">
                                    <i class="ph-bold ph-user-circle mr-1 text-blue-400 text-sm"></i> {{ $item->penulis }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-white border border-gray-200 text-gray-500">
                                {{ $item->kategori }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 flex flex-col items-center justify-center border border-gray-100">
                                    <span class="text-xs font-black text-gray-900">{{ $item->stok }}</span>
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
                                    class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100">
                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                </button>
                                
                                <form action="{{ route('buku.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus koleksi ini?')" class="inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-100">
                                        <i class="ph-bold ph-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center text-gray-400">Rak buku masih kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <template x-teleport="body">
        <div>
            <div x-show="openModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div @click="openModal = false" class="absolute inset-0 bg-gray-900/60 backdrop-blur-md"></div>
                <div class="bg-white w-full max-w-xl rounded-[3rem] p-10 shadow-2xl relative z-10"
                     x-show="openModal" x-transition>
                    <h3 class="text-2xl font-black text-gray-900 mb-6">Tambah Koleksi</h3>
                    <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <input type="text" name="judul" placeholder="Judul Buku" class="w-full px-5 py-4 bg-gray-50 rounded-[1.2rem] outline-none focus:ring-2 focus:ring-blue-500" required>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="penulis" placeholder="Penulis" class="px-5 py-4 bg-gray-50 rounded-[1.2rem] outline-none" required>
                            <select name="kategori" class="px-5 py-4 bg-gray-50 rounded-[1.2rem] outline-none" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat) <option value="{{ $cat }}">{{ $cat }}</option> @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="number" name="stok" placeholder="Stok" class="px-5 py-4 bg-gray-50 rounded-[1.2rem] outline-none" required>
                            <input type="file" name="cover" class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700" required>
                        </div>
                        <div class="flex gap-4 pt-4">
                            <button type="submit" class="flex-1 bg-gray-900 text-white font-black py-4 rounded-[1.5rem]">Simpan</button>
                            <button type="button" @click="openModal = false" class="px-8 py-4 bg-gray-100 text-gray-400 font-black rounded-[1.5rem]">Batal</button>
                        </div>
                    </form>
                </div>
            </div>

            <div x-show="openEditModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div @click="openEditModal = false" class="absolute inset-0 bg-gray-900/60 backdrop-blur-md"></div>
                <div class="bg-white w-full max-w-xl rounded-[3rem] p-10 shadow-2xl relative z-10"
                     x-show="openEditModal" x-transition>
                    <h3 class="text-2xl font-black text-gray-900 mb-6">Edit Literatur</h3>
                    <form :action="'/buku/' + editData.id" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf @method('PUT')
                        <input type="text" name="judul" x-model="editData.judul" class="w-full px-5 py-4 bg-gray-50 rounded-[1.2rem] outline-none" required>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="penulis" x-model="editData.penulis" class="px-5 py-4 bg-gray-50 rounded-[1.2rem] outline-none" required>
                            <select name="kategori" x-model="editData.kategori" class="px-5 py-4 bg-gray-50 rounded-[1.2rem] outline-none" required>
                                @foreach($categories as $cat) <option value="{{ $cat }}">{{ $cat }}</option> @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="number" name="stok" x-model="editData.stok" class="px-5 py-4 bg-gray-50 rounded-[1.2rem] outline-none" required>
                            <input type="file" name="cover" class="text-xs text-gray-500">
                        </div>
                        <div class="flex gap-4 pt-4">
                            <button type="submit" class="flex-1 bg-blue-600 text-white font-black py-4 rounded-[1.5rem]">Update</button>
                            <button type="button" @click="openEditModal = false" class="px-8 py-4 bg-gray-100 text-gray-400 font-black rounded-[1.5rem]">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection