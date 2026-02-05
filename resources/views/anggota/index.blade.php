@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div x-data="{ openModal: false, openEditModal: false, editData: {} }" class="space-y-6">
    
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Data Anggota (Siswa)</h2>
            <p class="text-gray-400 text-sm">Kelola akun login untuk member perpustakaan Anda.</p>
        </div>
        <button @click="openModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm flex items-center transition-all shadow-lg shadow-indigo-100">
            <i class="ph-bold ph-user-plus mr-2"></i> Tambah Anggota
        </button>
    </div>

    <div class="bg-white rounded-4x1 border border-gray-100 overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-gray-400 text-[11px] uppercase tracking-widest font-bold">
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Kontak</th>
                    <th class="px-6 py-4">Alamat</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($anggota as $item)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $item->name }}</td> 
                    <td class="px-6 py-4">
                        <p class="text-gray-600">{{ $item->email }}</p>
                        <p class="text-[11px] text-gray-400">{{ $item->telepon ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $item->alamat ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center items-center gap-2">
                            <button @click="openEditModal = true; editData = { id: '{{ $item->id }}', name: '{{ $item->name }}', email: '{{ $item->email }}', telepon: '{{ $item->telepon }}', alamat: '{{ $item->alamat }}' }" 
                               class="flex items-center justify-center w-9 h-9 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                            </button>

                            <form action="{{ route('anggota.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus akun siswa ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="flex items-center justify-center w-9 h-9 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all">
                                    <i class="ph-bold ph-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">Belum ada akun siswa terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="openModal" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" x-transition>
        <div @click.away="openModal = false" class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl relative">
            <h3 class="text-xl font-extrabold text-gray-900 mb-6">Daftarkan Akun Siswa</h3>
            <form action="{{ route('anggota.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Nama Lengkap</label>
                    <input type="text" name="name" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Email Akun</label>
                        <input type="email" name="email" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Password</label>
                        <input type="password" name="password" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none" placeholder="Min. 8 Karakter" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Nomor Telepon</label>
                        <input type="text" name="telepon" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Alamat Singkat</label>
                        <input type="text" name="alamat" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none" required>
                    </div>
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-indigo-100">Buat Akun Siswa</button>
                    <button type="button" @click="openModal = false" class="px-6 py-3.5 bg-gray-100 text-gray-400 font-bold rounded-xl">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="openEditModal" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" x-transition>
        <div @click.away="openEditModal = false" class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl relative">
            <h3 class="text-xl font-extrabold text-gray-900 mb-6">Edit Informasi Siswa</h3>
            <form :action="'/anggota/' + editData.id" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Nama Lengkap</label>
                    <input type="text" name="name" x-model="editData.name" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Email Akun</label>
                        <input type="email" name="email" x-model="editData.email" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none" >
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Nomor Telepon</label>
                        <input type="text" name="telepon" x-model="editData.telepon" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Alamat</label>
                        <input type="text" name="alamat" x-model="editData.alamat" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none">
                    </div>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-100">Simpan Perubahan</button>
                    <button type="button" @click="openEditModal = false" class="px-6 py-3.5 bg-gray-100 text-gray-400 font-bold rounded-xl">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection