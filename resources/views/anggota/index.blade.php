@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

{{-- ALERT SYSTEM CENTERED --}}
<div x-data="{ show: true }" 
     x-show="show" 
     x-cloak
     class="fixed inset-0 z-[200] flex items-center justify-center p-6 pointer-events-none">
    
    {{-- Alert Berhasil (Success) --}}
    @if(session('success'))
    <div x-show="show" 
         x-init="setTimeout(() => show = false, 4000)"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90"
         class="w-full max-w-sm bg-white dark:bg-slate-900 border-2 border-emerald-500 rounded-[2.5rem] p-8 shadow-[0_20px_60px_rgba(0,0,0,0.3)] text-center pointer-events-auto relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-2 bg-emerald-500"></div>
        
        <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-900/30 rounded-3xl flex items-center justify-center text-emerald-500 mx-auto mb-6">
            <i class="ph-fill ph-check-circle text-5xl"></i>
        </div>
        
        <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter mb-2">Berhasil!</h3>
        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 leading-relaxed">{{ session('success') }}</p>
        
        <button @click="show = false" class="mt-8 w-full py-4 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-black rounded-2xl text-[10px] uppercase tracking-[0.2em] hover:bg-slate-200 transition-colors">
            Tutup
        </button>
    </div>
    @endif

    {{-- Alert Gagal (Validation Errors) --}}
    @if($errors->any())
    <div x-show="show" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         class="w-full max-w-sm bg-white dark:bg-slate-900 border-2 border-red-500 rounded-[2.5rem] p-8 shadow-[0_20px_60px_rgba(0,0,0,0.3)] pointer-events-auto relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-2 bg-red-500"></div>
        
        <div class="w-20 h-20 bg-red-50 dark:bg-red-900/30 rounded-3xl flex items-center justify-center text-red-500 mx-auto mb-6 text-center">
            <i class="ph-fill ph-warning-circle text-5xl"></i>
        </div>
        
        <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter mb-4 text-center">Ada Masalah!</h3>
        
        <ul class="space-y-2 mb-8 text-left">
            @foreach ($errors->all() as $error)
                <li class="flex items-center gap-3">
                    <div class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0"></div>
                    <span class="text-[11px] font-bold text-slate-600 dark:text-slate-300 leading-tight">{{ $error }}</span>
                </li>
            @endforeach
        </ul>
        
        <button @click="show = false" class="w-full py-4 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-black rounded-2xl text-[10px] uppercase tracking-[0.2em] hover:bg-red-100 transition-colors">
            Perbaiki Data
        </button>
    </div>
    @endif
</div>

<div x-data="{ 
    openModal: false, 
    openEditModal: false, 
    editData: {id: '', username: '', name: '', email: '', telepon: '', alamat: ''} 
}" class="max-w-7xl mx-auto space-y-8 py-6 px-4 animate-in fade-in duration-500">
    
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter uppercase italic">
                Kelola <span class="text-indigo-600">Anggota</span>
            </h2>
            <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-[0.3em] mt-2 border-l-4 border-indigo-600 pl-4">
                Manajemen Akses & Informasi Akun Siswa
            </p>
        </div>
        <button @click="openModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all shadow-2xl shadow-indigo-200 dark:shadow-none active:scale-95 flex items-center gap-3">
            <i class="ph-bold ph-user-plus text-lg"></i> 
            Tambah Anggota
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-800">
                        <th class="px-10 py-6 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 dark:text-slate-500">Profil & Username</th>
                        <th class="px-6 py-6 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 dark:text-slate-500">Informasi Kontak</th>
                        <th class="px-6 py-6 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 dark:text-slate-500">Domisili</th>
                        <th class="px-10 py-6 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 dark:text-slate-500 text-right">Opsi Pengelolaan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-800">
                    @forelse($anggota as $item)
                    <tr class="hover:bg-indigo-50/20 dark:hover:bg-indigo-900/5 transition-colors group">
                        <td class="px-10 py-6">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-black text-lg shadow-lg group-hover:rotate-3 transition-transform shrink-0">
                                    {{ strtoupper(substr($item->name, 0, 2)) }}
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="font-black text-gray-900 dark:text-white text-lg tracking-tight uppercase group-hover:text-indigo-600 transition-colors truncate">{{ $item->name }}</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 px-2 py-0.5 rounded-md font-black italic">@ {{ $item->username }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex flex-col space-y-1">
                                <div class="flex items-center text-gray-700 dark:text-slate-300 font-bold text-sm">
                                    <i class="ph-bold ph-envelope-simple mr-2 text-indigo-400"></i>
                                    {{ $item->email }}
                                </div>
                                <div class="flex items-center text-gray-400 dark:text-slate-500 text-[11px] font-medium tracking-wide">
                                    <i class="ph-bold ph-whatsapp-logo mr-2 text-green-500"></i>
                                    {{ $item->telepon ?? 'No Contact' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <div class="max-w-[250px] text-gray-500 dark:text-slate-400 text-xs leading-relaxed italic border-l-2 border-gray-100 dark:border-slate-800 pl-4">
                                {{ $item->alamat ?? 'Alamat belum dilengkapi' }}
                            </div>
                        </td>
                        <td class="px-10 py-6 text-right">
                            <div class="flex justify-end gap-3">
                                <button @click="editData = { id: '{{ $item->id }}', username: '{{ $item->username }}', name: '{{ addslashes($item->name) }}', email: '{{ $item->email }}', telepon: '{{ $item->telepon }}', alamat: '{{ addslashes($item->alamat) }}' }; openEditModal = true;" 
                                    class="w-11 h-11 flex items-center justify-center bg-white dark:bg-slate-800 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm border border-gray-100 dark:border-slate-700">
                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                </button>
                                
                                <form action="{{ route('anggota.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus permanen akun siswa ini?')" class="inline">
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
                        <td colspan="4" class="px-8 py-32 text-center">
                            <i class="ph-bold ph-users-three text-4xl text-gray-200 mb-4 block"></i>
                            <p class="text-gray-300 dark:text-slate-700 text-[11px] font-black uppercase tracking-[0.5em]">Tidak ada data siswa ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modals --}}
    <template x-teleport="body">
        <div>
            {{-- MODAL CREATE --}}
            <div x-show="openModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div @click="openModal = false" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
                <div class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-[3rem] p-12 shadow-2xl relative z-10 border dark:border-slate-800"
                     x-show="openModal" x-transition:enter="ease-out duration-300 scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100">
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-8 uppercase tracking-tighter italic">Registrasi <span class="text-indigo-600">Siswa</span></h3>
                    <form action="{{ route('anggota.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Nama Lengkap Siswa</label>
                                <input type="text" name="name" class="w-full px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none focus:ring-2 focus:ring-indigo-600 border-none font-bold uppercase" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Username</label>
                                <input type="text" name="username" class="w-full px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none focus:ring-2 focus:ring-indigo-600 border-none font-bold italic" placeholder="Username" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Email Institusi</label>
                                <input type="email" name="email" class="w-full px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none border-none font-bold" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Access Password</label>
                                <input type="password" name="password" class="w-full px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none border-none font-bold" placeholder="••••••••" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">WhatsApp</label>
                                <input type="text" name="telepon" class="w-full px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none border-none font-bold" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Alamat Singkat</label>
                                <input type="text" name="alamat" class="w-full px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none border-none font-bold" required>
                            </div>
                        </div>
                        <div class="flex gap-4 pt-6">
                            <button type="submit" class="flex-1 bg-indigo-600 text-white font-black py-5 rounded-2xl hover:bg-indigo-700 transition-all uppercase tracking-widest text-xs shadow-xl shadow-indigo-100 dark:shadow-none">Aktifkan Akun</button>
                            <button type="button" @click="openModal = false" class="px-10 py-5 bg-gray-100 dark:bg-slate-800 text-gray-400 font-black rounded-2xl hover:bg-gray-200 transition-all uppercase tracking-widest text-xs">Batal</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- MODAL EDIT --}}
            <div x-show="openEditModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div @click="openEditModal = false" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
                <div class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-[3rem] p-12 shadow-2xl relative z-10 border dark:border-slate-800"
                     x-show="openEditModal" x-transition:enter="ease-out duration-300 scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100">
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-8 uppercase tracking-tighter italic">Update <span class="text-indigo-600">Profile</span></h3>
                    <form :action="'/anggota/' + editData.id" method="POST" class="space-y-6">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Nama Lengkap</label>
                                <input type="text" name="name" x-model="editData.name" class="w-full px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none border-none font-bold uppercase focus:ring-2 focus:ring-indigo-600" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Username</label>
                                <input type="text" name="username" x-model="editData.username" class="w-full px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none border-none font-bold italic focus:ring-2 focus:ring-indigo-600" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Email</label>
                                <input type="email" name="email" x-model="editData.email" class="w-full px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none border-none font-bold" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Password Baru (Opsional)</label>
                                <input type="password" name="password" class="w-full px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none border-none font-bold" placeholder="••••••••">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Telepon</label>
                                <input type="text" name="telepon" x-model="editData.telepon" class="w-full px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none border-none font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Alamat</label>
                                <input type="text" name="alamat" x-model="editData.alamat" class="w-full px-6 py-5 bg-gray-50 dark:bg-slate-800 dark:text-white rounded-2xl outline-none border-none font-bold">
                            </div>
                        </div>
                        <div class="flex gap-4 pt-6">
                            <button type="submit" class="flex-1 bg-indigo-600 text-white font-black py-5 rounded-2xl hover:bg-indigo-700 transition-all uppercase tracking-widest text-xs">Simpan Perubahan</button>
                            <button type="button" @click="openEditModal = false" class="px-10 py-5 bg-gray-100 dark:bg-slate-800 text-gray-400 font-black rounded-2xl hover:bg-gray-200 transition-all uppercase tracking-widest text-xs">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection