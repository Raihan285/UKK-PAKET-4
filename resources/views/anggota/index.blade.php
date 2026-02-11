@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div x-data="{ 
    openModal: false, 
    openEditModal: false, 
    editData: {id: '', name: '', email: '', telepon: '', alamat: ''} 
}" class="max-w-7xl mx-auto space-y-8 animate-in fade-in duration-500">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Data Anggota</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola akses dan informasi akun siswa perpustakaan.</p>
        </div>
        <button @click="openModal = true" class="group bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl font-bold text-sm flex items-center transition-all duration-300 shadow-xl shadow-indigo-100 active:scale-95">
            <i class="ph-bold ph-user-plus mr-2 group-hover:scale-110 transition-transform"></i> 
            Tambah Anggota Baru
        </button>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Identitas Siswa</th>
                        <th class="px-6 py-5 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Kontak</th>
                        <th class="px-6 py-5 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Alamat</th>
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($anggota as $item)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        <td class="px-8 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-black text-sm border-2 border-white shadow-sm">
                                    {{ strtoupper(substr($item->name, 0, 2)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-black text-gray-900 text-base mb-0.5">{{ $item->name }}</span>
                                    <span class="text-[10px] text-indigo-500 font-bold uppercase tracking-tighter">Member Aktif</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center text-gray-700 font-semibold">
                                    <i class="ph-bold ph-envelope-simple mr-2 text-gray-400"></i>
                                    {{ $item->email }}
                                </div>
                                <div class="flex items-center text-gray-400 text-xs">
                                    <i class="ph-bold ph-phone mr-2 text-gray-300"></i>
                                    {{ $item->telepon ?? 'N/A' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-[200px] truncate text-gray-500 italic">
                                <i class="ph-bold ph-map-pin mr-1 text-indigo-300"></i>
                                {{ $item->alamat ?? '-' }}
                            </div>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <div class="flex justify-end items-center gap-3">
                                <button type="button" 
                                    @click="
                                        editData = { 
                                            id: '{{ $item->id }}', 
                                            name: '{{ addslashes($item->name) }}', 
                                            email: '{{ $item->email }}', 
                                            telepon: '{{ $item->telepon }}', 
                                            alamat: '{{ addslashes($item->alamat) }}' 
                                        }; 
                                        openEditModal = true;
                                    " 
                                    class="w-10 h-10 flex items-center justify-center bg-white text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm border border-indigo-50 hover:border-indigo-600">
                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                </button>
                                
                                <form action="{{ route('anggota.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus akun siswa ini?')" class="inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 flex items-center justify-center bg-white text-red-400 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-50 hover:border-red-600">
                                        <i class="ph-bold ph-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center text-gray-400">Belum ada anggota terdaftar.</td>
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
                <div class="bg-white w-full max-w-xl rounded-[3rem] p-10 shadow-2xl relative z-10" x-show="openModal" x-transition>
                    <div class="mb-8">
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">Daftarkan Siswa</h3>
                        <p class="text-gray-400 text-sm">Akun ini akan digunakan siswa untuk login.</p>
                    </div>

                    <form action="{{ route('anggota.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Nama Lengkap</label>
                            <input type="text" name="name" class="w-full px-5 py-4 bg-gray-50 rounded-[1.2rem] outline-none focus:ring-2 focus:ring-indigo-500/20 font-bold" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Email Siswa</label>
                                <input type="email" name="email" class="w-full px-5 py-4 bg-gray-50 rounded-[1.2rem] outline-none font-bold" required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Password</label>
                                <input type="password" name="password" class="w-full px-5 py-4 bg-gray-50 rounded-[1.2rem] outline-none font-bold" placeholder="Min. 8 Karakter" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase ml-1">WhatsApp/Telepon</label>
                                <input type="text" name="telepon" class="w-full px-5 py-4 bg-gray-50 rounded-[1.2rem] outline-none font-bold" required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Alamat Singkat</label>
                                <input type="text" name="alamat" class="w-full px-5 py-4 bg-gray-50 rounded-[1.2rem] outline-none font-bold" required>
                            </div>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="submit" class="flex-1 bg-indigo-600 text-white font-black py-4 rounded-[1.5rem] shadow-lg shadow-indigo-100">Buat Akun</button>
                            <button type="button" @click="openModal = false" class="px-8 py-4 bg-gray-100 text-gray-400 font-black rounded-[1.5rem]">Batal</button>
                        </div>
                    </form>
                </div>
            </div>

            <div x-show="openEditModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div @click="openEditModal = false" class="absolute inset-0 bg-gray-900/60 backdrop-blur-md"></div>
                <div class="bg-white w-full max-w-xl rounded-[3rem] p-10 shadow-2xl relative z-10" x-show="openEditModal" x-transition>
                    <div class="mb-8">
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">Perbarui Informasi</h3>
                        <p class="text-gray-400 text-sm">Update data atau reset akses siswa.</p>
                    </div>

                    <form :action="'/anggota/' + editData.id" method="POST" class="space-y-5">
                        @csrf @method('PUT')
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Nama Lengkap</label>
                            <input type="text" name="name" x-model="editData.name" class="w-full px-5 py-4 bg-gray-50 rounded-[1.2rem] outline-none font-bold" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Email Akun</label>
                                <input type="email" name="email" x-model="editData.email" class="w-full px-5 py-4 bg-gray-50 rounded-[1.2rem] outline-none font-bold" required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Ganti Password (Isi jika perlu)</label>
                                <input type="password" name="password" class="w-full px-5 py-4 bg-gray-50 rounded-[1.2rem] outline-none font-bold" placeholder="Kosongkan jika tidak diubah">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Telepon</label>
                                <input type="text" name="telepon" x-model="editData.telepon" class="w-full px-5 py-4 bg-gray-50 rounded-[1.2rem] outline-none font-bold">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Alamat</label>
                                <input type="text" name="alamat" x-model="editData.alamat" class="w-full px-5 py-4 bg-gray-50 rounded-[1.2rem] outline-none font-bold">
                            </div>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="submit" class="flex-1 bg-indigo-600 text-white font-black py-4 rounded-[1.5rem] shadow-lg shadow-indigo-100">Simpan Perubahan</button>
                            <button type="button" @click="openEditModal = false" class="px-8 py-4 bg-gray-100 text-gray-400 font-black rounded-[1.5rem]">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection