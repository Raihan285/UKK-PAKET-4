<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booktify - Digital Library</title>
    
    {{-- CDN Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            darkMode: 'class', 
            theme: {
                extend: {
                    colors: {   
                        indigo: { 950: '#0f172a' }
                    }
                }
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/phosphor-icons@2.1.0"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    {{-- Logika Dark Mode --}}
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();

        function toggleDarkMode() {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        }
    </script>

    {{-- Script Global Search --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('globalSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const targets = document.querySelectorAll('tbody tr, .searchable-item');

                targets.forEach(el => {
                    const text = el.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        el.style.display = el.tagName === 'TR' ? 'table-row' : 'block';
                        el.style.opacity = '1';
                    } else {
                        el.style.display = 'none';
                    }
                });
            });
        }
    });
    </script>

    <style type="text/tailwindcss">
        @layer base {
            body {
                @apply transition-colors duration-500 ease-in-out;
            }
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-50 dark:bg-slate-950 font-sans text-gray-900 dark:text-gray-100 no-scrollbar" x-data="{ sidebarOpen: false }">
    
    <div class="flex h-screen overflow-hidden relative">
        
        {{-- Overlay Untuk Menutup Sidebar di Mobile --}}
        <div x-cloak 
             x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             class="lg:hidden fixed inset-0 bg-black/50 z-[45] backdrop-blur-sm transition-all duration-300">
        </div>

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">
            
            {{-- HEADER (Search Dan Dark Mode) --}}
            <header class="h-20 flex items-center justify-between px-4 md:px-8 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b dark:border-slate-800 transition-all z-40">
                
                {{-- Tombol Hamburger --}}
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="lg:hidden p-2.5 bg-gray-100 dark:bg-slate-800 rounded-xl text-gray-600 dark:text-gray-300 hover:scale-105 active:scale-95 transition-all">
                        <i class="ph-bold ph-list text-xl"></i>
                    </button>

                    {{-- Search Bar --}}
                    <div class="relative w-full max-w-[180px] sm:max-w-md group">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
                        <input type="text" 
                               id="globalSearch" 
                               placeholder="{{ __('Cari...') }}" 
                               class="w-full bg-gray-100 dark:bg-slate-800/50 border-none rounded-2xl py-2 pl-10 pr-4 text-xs sm:text-sm focus:ring-2 focus:ring-indigo-500/20 dark:text-white transition-all outline-none"
                        >
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex items-center gap-2 sm:gap-4">

                    {{-- Tombol Dark Mode --}}
                    <button onclick="toggleDarkMode()" type="button"
                        class="w-10 h-10 flex items-center justify-center bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm hover:scale-105 active:scale-95 transition-all">
                        <i class="ph-bold ph-moon text-indigo-600 dark:hidden text-lg"></i>
                        <i class="ph-bold ph-sun text-yellow-400 hidden dark:block text-lg"></i>
                    </button>

                    {{-- Profile Dropdown --}}
                    <div class="flex items-center gap-2 sm:gap-3 pl-2 sm:pl-4 border-l dark:border-slate-800" x-data="{ open: false }">
                        <div class="text-right hidden sm:block leading-tight">
                            <p class="text-xs font-black text-gray-800 dark:text-white line-clamp-1">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ Auth::user()->role }}</p>
                        </div>
                        
                        <div class="relative">
                            <button @click="open = !open" @click.away="open = false" class="relative group focus:outline-none flex">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff" 
                                     class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl border-2 border-white dark:border-slate-800 shadow-sm group-hover:border-indigo-500 transition-all">
                            </button>

                            {{-- Menu Dropdown --}}
                            <div x-show="open" 
                                 x-transition 
                                 class="absolute right-0 mt-3 w-48 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-700 py-2 z-50"
                                 x-cloak>
                                
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors font-bold text-left">
                                        <i class="ph-bold ph-sign-out text-lg"></i>
                                        <span>{{ __('Keluar') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Konten Utama --}}
            <main class="flex-1 overflow-y-auto p-4 md:p-8 no-scrollbar">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

</body>
</html>