@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex justify-between items-end border-b border-gray-100 pb-6">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">
                {{ Auth::user()->role == 'admin' ? 'Kelola Transaksi' : 'Daftar Pinjaman' }}
            </h2>
            <p class="text-gray-400 text-sm font-medium">
                {{ Auth::user()->role == 'admin' ? 'Daftar pengajuan buku dari seluruh siswa' : 'Daftar buku yang sedang Anda Pinjam atau telah anda kembalikan.' }}
            </p>
        </div>
    </div>

    @if(Auth::user()->role == 'admin')
        <div class="bg-white rounded-4x1 border border-gray-100 overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50">
                    <tr class="text-[10px] uppercase tracking-widest font-bold text-gray-400">
                        <th class="px-6 py-4">Cover</th>
                        <th class="px-6 py-4">Siswa</th>
                        <th class="px-6 py-4">Buku</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Batas Kembali</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-xs">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50/50 transition-all">
                        <td class="px-6 py-4">
                            <div class="w-10 h-14 bg-gray-100 rounded-lg overflow-hidden shadow-sm">
                                <img src="{{ asset('storage/' . $trx->book->cover) }}" class="w-full h-full object-cover">
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 font-bold text-blue-600">{{ $trx->user->name }}</td>
                        
                        <td class="px-6 py-4">
                            <p class="font-black text-gray-900 leading-tight">{{ $trx->book->judul }}</p>
                            <p class="text-[9px] text-gray-400 uppercase mt-0.5">{{ $trx->book->kategori }}</p>
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-md text-[9px] font-black uppercase 
                                {{ $trx->status == 'menunggu' ? 'bg-amber-50 text-amber-500' : ($trx->status == 'dipinjam' ? 'bg-blue-50 text-blue-600' : 'bg-emerald-50 text-emerald-500') }}">
                                {{ $trx->status }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-gray-400 font-medium">
                            {{ $trx->tanggal_kembali ? \Carbon\Carbon::parse($trx->tanggal_kembali)->format('d M Y') : '-' }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($trx->status == 'menunggu')
                            <form action="{{ route('transaksi.approve', $trx->id) }}" method="POST" class="inline">
                                @csrf
                                <button class="bg-gray-900 text-white px-4 py-1.5 rounded-lg text-[10px] font-bold hover:bg-blue-600 transition-all shadow-md">
                                    Setujui Pinjam
                                </button>
                            </form>
                            @elseif($trx->status == 'dipinjam')
                                <span class="text-[10px] text-blue-500 font-bold uppercase tracking-tighter">Dalam Peminjaman</span>
                            @else
                                <span class="text-[10px] text-gray-300 italic">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic font-medium">
                            Belum ada pengajuan peminjaman baru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($transactions as $trx)
            <div class="bg-white p-5 rounded-4x1 border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="flex gap-4">
                    <div class="w-20 h-28 bg-gray-100 rounded-2xl overflow-hidden shrink-0 shadow-sm">
                        <img src="{{ asset('storage/' . $trx->book->cover) }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col justify-center">
                        <span class="text-[9px] font-bold text-blue-600 uppercase tracking-widest mb-1">{{ $trx->book->kategori ?? 'Umum' }}</span>
                        <h4 class="text-sm font-black text-gray-900 leading-tight mb-1">{{ $trx->book->judul }}</h4>
                        <p class="text-[10px] text-gray-400 font-medium italic">Status: <span class="text-gray-600 font-bold uppercase">{{ $trx->status }}</span></p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-50 flex justify-between items-center">
                    <span class="px-3 py-1 rounded-lg text-[9px] font-bold uppercase {{ $trx->status == 'dipinjam' ? 'bg-blue-50 text-blue-600' : ($trx->status == 'menunggu' ? 'bg-amber-50 text-amber-500' : 'bg-emerald-50 text-emerald-500') }}">
                        {{ $trx->status }}
                    </span>
                    @if($trx->status == 'dipinjam')
                        <p class="text-[9px] text-red-400 font-bold italic">Batas: {{ \Carbon\Carbon::parse($trx->tanggal_kembali)->format('d/m/Y') }}</p>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center">
                <p class="text-gray-400 text-xs italic font-medium">Anda belum memiliki riwayat peminjaman buku.</p>
            </div>
            @endforelse
        </div>
    @endif
</div>
@endsection