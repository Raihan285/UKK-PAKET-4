@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-8 bg-gray-50/50 min-h-screen">
    <div class="mb-10">
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Pengaturan Sistem</h2>
        <p class="text-gray-500 mt-1 font-medium">Kelola denda, kategori buku, dan rekomendasi.</p>
    </div>

    @if(session('success'))
        <div class="mb-8 bg-emerald-50 text-emerald-700 p-4 rounded-2xl font-bold text-sm border border-emerald-100 flex items-center shadow-sm">
            <i class="ph-bold ph-check-circle mr-3 text-xl"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-xl shadow-gray-200/50 transition-all hover:shadow-2xl hover:shadow-gray-200/60">
            <div class="flex items-center space-x-4 mb-8">
                <div class="p-4 bg-blue-50 text-blue-600 rounded-2xl shadow-inner">
                    <i class="ph-bold ph-calendar-check text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-black text-gray-800 text-lg">Denda & Waktu Pinjam</h3>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Atur Parameter Dasar</p>
                </div>
            </div>
            
            <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
                @csrf
                <div class="group">
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Batas Waktu Pinjam (Hari)</label>
                    <input type="number" name="batas_hari" value="{{ $setting->batas_hari }}" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white focus:ring-0 transition-all font-bold text-gray-700 shadow-sm">
                </div>
                <div class="group">
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Denda Per Hari (Rp)</label>
                    <input type="number" name="denda_per_hari" value="{{ $setting->denda_per_hari }}" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white focus:ring-0 transition-all font-bold text-gray-700 shadow-sm">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-5 rounded-2xl font-black shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-1 transition-all active:scale-[0.98]">
                    SIMPAN PENGATURAN
                </button>
            </form>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-xl shadow-gray-200/50 flex flex-col transition-all hover:shadow-2xl hover:shadow-gray-200/60">
            <div class="flex items-center space-x-4 mb-8">
                <div class="p-4 bg-indigo-50 text-indigo-600 rounded-2xl shadow-inner">
                    <i class="ph-bold ph-tag text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-black text-gray-800 text-lg">Daftar Kategori Buku</h3>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Kelola Klasifikasi</p>
                </div>
            </div>

            <form action="{{ route('settings.category.store') }}" method="POST" class="flex space-x-3 mb-8">
                @csrf
                <input type="text" name="nama_kategori" placeholder="Tambah kategori..." class="flex-1 px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-indigo-500 focus:bg-white transition-all font-bold text-gray-700 shadow-sm">
                <button type="submit" class="bg-indigo-600 text-white px-8 rounded-2xl font-black hover:bg-indigo-700 transition-all active:scale-95 shadow-lg shadow-indigo-100">
                    TAMBAH
                </button>
            </form>

            <div class="flex flex-wrap gap-3 overflow-y-auto max-h-[200px] p-1">
                @php 
                    $kategoris = array_filter($setting->daftar_kategori ?? []); 
                @endphp

                @forelse($kategoris as $k)
                    <div class="inline-flex items-center bg-gray-50 hover:bg-white hover:border-indigo-200 border border-gray-100 px-4 py-2.5 rounded-xl transition-all group shadow-sm">
                        <span class="text-sm font-black text-gray-700 mr-3">{{ $k }}</span>
                        <form action="{{ route('settings.category.destroy', ['category' => $k]) }}" method="POST" class="leading-none">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus kategori {{ $k }}?')" class="text-gray-300 hover:text-red-500 transition-colors">
                                <i class="ph-bold ph-x-circle text-lg leading-none"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="w-full py-8 text-center border-2 border-dashed border-gray-100 rounded-3xl">
                        <p class="text-gray-400 font-bold text-sm">Belum ada kategori buku.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-xl shadow-gray-200/50">
            <div class="flex items-center space-x-4 mb-8">
                <div class="p-4 bg-amber-50 text-amber-600 rounded-2xl shadow-inner">
                    <i class="ph-bold ph-star text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-black text-gray-800 text-lg">Tandai Buku Rekomendasi</h3>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Muncul di Halaman Utama</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-50">
                            <th class="pb-5 px-4">Judul Buku</th>
                            <th class="pb-5 px-4">Penulis</th>
                            <th class="pb-5 px-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($books as $book)
                        <tr class="group hover:bg-gray-50/50 transition-colors">
                            <td class="py-5 px-4">
                                <span class="font-black text-gray-700 block">{{ $book->judul }}</span>
                                <span class="text-[10px] text-indigo-500 font-black uppercase">{{ $book->kategori ?? 'Umum' }}</span>
                            </td>
                            <td class="py-5 px-4 text-gray-500 font-bold text-sm">{{ $book->penulis }}</td>
                            <td class="py-5 px-4 text-center">
                                <form action="{{ route('settings.recommendation.toggle', $book->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-5 py-2.5 rounded-xl text-[10px] font-black tracking-widest transition-all {{ $book->is_recommended ? 'bg-amber-100 text-amber-600 border border-amber-200' : 'bg-gray-100 text-gray-400 border border-transparent hover:bg-gray-200 hover:text-gray-600' }}">
                                        {{ $book->is_recommended ? 'REKOMENDASI' : 'BIASA' }}
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
@endsection