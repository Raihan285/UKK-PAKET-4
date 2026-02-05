<aside class="w-64 bg-white border-r flex flex-col pt-0 pb-6 sticky top-0 h-screen">
    <div class="px-4 -mt-4 mb-0">
        <img src="{{ asset('images/logobooktify.png') }}" alt="Booktify" class="w-full h-auto block object-contain">
    </div>
    
    <nav class="flex-1 px-4 -mt-8 space-y-1">
        <div class="mb-1 px-4 text-[10px] font-bold text-gray-300 uppercase tracking-widest">Menu Utama</div>
        
        <a href="{{ route('home') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:bg-gray-50 hover:text-gray-600' }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-house text-lg"></i> 
            <span>Beranda</span>
        </a>
        
        <a href="{{ route('daftar-buku.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('daftar-buku.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:bg-gray-50 hover:text-gray-600' }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-book-open text-lg"></i> 
            <span>Daftar Buku</span>
        </a>

        @if(Auth::user()->role != 'admin')
        <a href="{{ route('transaksi.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('transaksi.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:bg-gray-50 hover:text-gray-600' }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-hand-pointing text-lg"></i> 
            <span>Peminjaman</span>
        </a>
        @endif
        
        @if(Auth::user()->role == 'admin')
        <div class="mt-6 mb-1 px-4 text-[10px] font-bold text-gray-300 uppercase tracking-widest">Administrator</div>
        
        <a href="{{ route('transaksi.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('transaksi.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:bg-gray-50 hover:text-gray-600' }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-check-square text-lg"></i> 
            <span>Persetujuan</span>
        </a>

        <a href="{{ route('transaksi.pengembalian') }}" 
           class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('transaksi.pengembalian') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:bg-gray-50 hover:text-gray-600' }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-arrow-counter-clockwise text-lg"></i> 
            <span>Pengembalian</span>
        </a>

        <a href="{{ route('buku.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('buku.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:bg-gray-50 hover:text-gray-600' }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-notebook text-lg"></i> 
            <span>Kelola Buku</span>
        </a>

        <a href="{{ route('anggota.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('anggota.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:bg-gray-50 hover:text-gray-600' }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-users text-lg"></i> 
            <span>Kelola Anggota</span>
        </a>
        @endif

        @if(Auth::user()->role == 'admin')
        <div class="mt-6 mb-1 px-4 text-[10px] font-bold text-gray-300 uppercase tracking-widest">Sistem</div>

        <a href="{{ route('settings.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('settings.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:bg-gray-50' }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-gear text-lg"></i> 
            <span>Pengaturan</span>
        </a>
        @endif
    </nav>
</aside>