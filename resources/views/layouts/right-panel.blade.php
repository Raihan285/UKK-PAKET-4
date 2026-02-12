<aside class="w-80 bg-white dark:bg-slate-900 border-l dark:border-slate-800 p-8 flex flex-col transition-colors duration-500">
    {{-- Info Profil --}}
    <div class="flex items-center justify-end space-x-3 mb-8">
        <div class="text-right">
            <p class="text-xs font-bold text-gray-900 dark:text-white transition-colors">{{ Auth::user()->name }}</p>
            <p class="text-[10px] text-gray-400 dark:text-slate-500 italic capitalize transition-colors">{{ Auth::user()->role }}</p>
        </div>
        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=fdf2f2&color=f97316" 
             class="w-10 h-10 rounded-full border-2 border-orange-100 dark:border-slate-700 transition-colors">
    </div>

    {{-- Pencarian Global --}}
    <div class="relative mb-10">
        <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500 transition-colors"></i>
        <input type="text" 
               id="globalSearch" 
               placeholder="Cari..." 
               class="w-full bg-gray-50 dark:bg-slate-800 border-none rounded-2xl py-3 pl-12 pr-4 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-100 dark:focus:ring-indigo-500/20 transition-all">
    </div>

    <div class="mt-auto space-y-6">
        {{-- Box Ganti Bahasa --}}
        <div class="p-4 bg-gray-50 dark:bg-slate-800/50 rounded-2xl border border-dashed border-gray-200 dark:border-slate-700 transition-colors">
            <p class="text-[10px] text-gray-400 dark:text-slate-500 mb-3 font-semibold uppercase tracking-wider transition-colors">Ganti Bahasa</p>
            <div class="flex gap-2">
                <button class="flex-1 bg-white dark:bg-slate-700 border border-orange-200 dark:border-slate-600 text-[10px] text-gray-900 dark:text-white py-2 rounded-lg font-bold flex items-center justify-center gap-2 shadow-sm transition-all hover:scale-105">
                    <img src="https://flagcdn.com/id.svg" class="w-4"> ID
                </button>
                <button class="flex-1 text-gray-400 dark:text-slate-500 text-[10px] py-2 rounded-lg font-bold flex items-center justify-center gap-2 hover:bg-white/10 transition-all">
                    <img src="https://flagcdn.com/gb.svg" class="w-4 opacity-40"> EN
                </button>
            </div>
        </div>

        {{-- Tombol Logout --}}
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center space-x-2 p-3 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all font-semibold text-sm">
                <i class="ph ph-sign-out"></i> <span>Logout</span>
            </button>
        </form>
    </div>
</aside>