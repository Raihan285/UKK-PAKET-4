@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div x-data="{ openModal: false, openEditModal: false, editData: {} }" class="space-y-6">
    
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Kelola Buku</h2>
            <p class="text-gray-400 text-sm">Tambah atau perbarui data buku koleksi perpustakaan.</p>
        </div>
        <button @click="openModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm flex items-center transition-all shadow-lg shadow-blue-100">
            <i class="ph-bold ph-plus mr-2"></i> Tambah Buku
        </button>
    </div>

    <div class="bg-white rounded-[2rem] border border-gray-100 overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-gray-400 text-[11px] uppercase tracking-widest font-bold">
                    <th class="px-6 py-4">Cover</th>
                    <th class="px-6 py-4">Informasi Buku</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Stok</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($buku as $item)
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    <td class="px-6 py-4">
                        <img src="{{ asset('storage/' . $item->cover) }}" class="w-12 h-16 object-cover rounded-lg shadow-sm group-hover:scale-105 transition-transform">
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-gray-900">{{ $item->judul }}</p>
                        <p class="text-xs text-gray-400">{{ $item->penulis }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-50 text-blue-500 text-[10px] font-bold px-3 py-1 rounded-full border border-blue-100 uppercase">
                            {{ $item->kategori }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-700">{{ $item->stok }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center items-center gap-2">
                            <button @click="openEditModal = true; editData = { id: '{{ $item->id }}', judul: '{{ $item->judul }}', penulis: '{{ $item->penulis }}', kategori: '{{ $item->kategori }}', stok: '{{ $item->stok }}' }" 
                               class="flex items-center justify-center w-9 h-9 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm">
                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                            </button>
                            <form action="{{ route('buku.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus buku ini?')" class="inline">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="flex items-center justify-center w-9 h-9 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all duration-300 shadow-sm">
                                    <i class="ph-bold ph-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Belum ada data buku.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div @click.away="openModal = false" class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl relative">
            <h3 class="text-xl font-extrabold text-gray-900 mb-6">Tambah Buku Baru</h3>
            <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Judul Buku</label>
                    <input type="text" name="judul" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Penulis</label>
                        <input type="text" name="penulis" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Kategori</label>
                        <select name="kategori" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Stok</label>
                        <input type="number" name="stok" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Cover</label>
                        <input type="file" name="cover" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700" required>
                    </div>
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3.5 rounded-xl">Simpan</button>
                    <button type="button" @click="openModal = false" class="px-6 py-3.5 bg-gray-100 text-gray-400 font-bold rounded-xl">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="openEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div @click.away="openEditModal = false" class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl relative">
            <h3 class="text-xl font-extrabold text-gray-900 mb-6">Edit Data Buku</h3>
            <form :action="'/buku/' + editData.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Judul Buku</label>
                    <input type="text" name="judul" x-model="editData.judul" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Penulis</label>
                        <input type="text" name="penulis" x-model="editData.penulis" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Kategori</label>
                        <select name="kategori" x-model="editData.kategori" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-blue-500" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Stok</label>
                        <input type="number" name="stok" x-model="editData.stok" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Cover (Opsional)</label>
                        <input type="file" name="cover" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700">
                    </div>
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3.5 rounded-xl">Update</button>
                    <button type="button" @click="openEditModal = false" class="px-6 py-3.5 bg-gray-100 text-gray-400 font-bold rounded-xl">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection