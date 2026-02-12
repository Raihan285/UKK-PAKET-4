<aside class="w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 flex flex-col pt-0 pb-6 sticky top-0 h-screen transition-colors duration-500">
    <div class="px-4 -mt-4 mb-0">
        {{-- Tips: Jika logo Anda punya versi dark mode, Anda bisa pakai filter invert atau ganti asset --}}
        <img src="{{ asset('images/logobooktify.png') }}" alt="Booktify" class="w-full h-auto block object-contain dark:brightness-110">
    </div>
    
    <nav class="flex-1 px-4 -mt-8 space-y-1 overflow-y-auto">
        <div class="mb-1 px-4 text-[10px] font-bold text-gray-300 dark:text-slate-600 uppercase tracking-widest">Menu Utama</div>
        
        {{-- BERANDA --}}
        <a href="{{ route('home') }}" class="flex items-center space-x-3 px-4 py-3 
            {{ request()->routeIs('home') ? 
                'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 
                'text-gray-400 dark:text-slate-500 hover:bg-gray-50 hover:text-gray-600 dark:hover:bg-slate-800 dark:hover:text-slate-200' 
            }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-house text-lg"></i> 
            <span>Beranda</span>
        </a>
        
        {{-- DAFTAR BUKU --}}
        <a href="{{ route('daftar_buku.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 
           {{ request()->routeIs('daftar_buku.*') ? 
                'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 
                'text-gray-400 dark:text-slate-500 hover:bg-gray-50 hover:text-gray-600 dark:hover:bg-slate-800 dark:hover:text-slate-200' 
           }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-book-open text-lg"></i> 
            <span>Daftar Buku</span>
        </a>

        @if(Auth::user()->role != 'admin')
        {{-- PEMINJAMAN (USER) --}}
        <a href="{{ route('transaksi.index') }}" class="flex items-center space-x-3 px-4 py-3 
            {{ request()->routeIs('transaksi.index') ? 
                'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 
                'text-gray-400 dark:text-slate-500 hover:bg-gray-50 hover:text-gray-600 dark:hover:bg-slate-800 dark:hover:text-slate-200' 
            }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-hand-pointing text-lg"></i> 
            <span>Peminjaman</span>
        </a>
        @endif
        
        @if(Auth::user()->role == 'admin')
        <div class="mt-6 mb-1 px-4 text-[10px] font-bold text-gray-300 dark:text-slate-600 uppercase tracking-widest">Administrator</div>
        
        {{-- STATISTIK GLOBAL --}}
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 
            {{ request()->routeIs('admin.dashboard') ? 
                'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 
                'text-gray-400 dark:text-slate-500 hover:bg-gray-50 dark:hover:bg-slate-800 dark:hover:text-slate-200' 
            }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-chart-line-up text-lg"></i>
            <span class="text-sm font-bold">Statistik Global</span>
        </a>

        {{-- PERSETUJUAN --}}
        <a href="{{ route('transaksi.index') }}" class="flex items-center space-x-3 px-4 py-3 
            {{ request()->routeIs('transaksi.index') ? 
                'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 
                'text-gray-400 dark:text-slate-500 hover:bg-gray-50 dark:hover:bg-slate-800 dark:hover:text-slate-200' 
            }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-check-square text-lg"></i> 
            <span>Persetujuan</span>
        </a>

        {{-- PENGEMBALIAN --}}
        <a href="{{ route('transaksi.pengembalian') }}" 
           class="flex items-center space-x-3 px-4 py-3 
           {{ request()->routeIs('transaksi.pengembalian') ? 
                'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 
                'text-gray-400 dark:text-slate-500 hover:bg-gray-50 dark:hover:bg-slate-800 dark:hover:text-slate-200' 
           }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-arrow-counter-clockwise text-lg"></i> 
            <span>Pengembalian</span>
        </a>

        {{-- KELOLA BUKU --}}
        <a href="{{ route('buku.index') }}" class="flex items-center space-x-3 px-4 py-3 
            {{ request()->routeIs('buku.*') ? 
                'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 
                'text-gray-400 dark:text-slate-500 hover:bg-gray-50 dark:hover:bg-slate-800 dark:hover:text-slate-200' 
            }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-notebook text-lg"></i> 
            <span>Kelola Buku</span>
        </a>

        {{-- KELOLA ANGGOTA --}}
        <a href="{{ route('anggota.index') }}" class="flex items-center space-x-3 px-4 py-3 
            {{ request()->routeIs('anggota.*') ? 
                'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 
                'text-gray-400 dark:text-slate-500 hover:bg-gray-50 dark:hover:bg-slate-800 dark:hover:text-slate-200' 
            }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-users text-lg"></i> 
            <span>Kelola Anggota</span>
        </a>
        @endif

        @if(Auth::user()->role == 'admin')
        <div class="mt-6 mb-1 px-4 text-[10px] font-bold text-gray-300 dark:text-slate-600 uppercase tracking-widest">Sistem</div>

        {{-- PENGATURAN --}}
        <a href="{{ route('settings.index') }}" class="flex items-center space-x-3 px-4 py-3 
            {{ request()->routeIs('settings.*') ? 
                'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 
                'text-gray-400 dark:text-slate-500 hover:bg-gray-50 dark:hover:bg-slate-800 dark:hover:text-slate-200' 
            }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-gear text-lg"></i> 
            <span>Pengaturan</span>
        </a>
        @endif
    </nav>
</aside>