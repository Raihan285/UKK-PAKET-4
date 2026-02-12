<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booktify - Digital Library</title>
    
    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            darkMode: 'class', 
            theme: {
                extend: {
                    colors: {
                    }
                }
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/phosphor-icons@2.1.0"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    {{-- Dark Mode Core Logic (Wajib di Head agar tidak flicker) --}}
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            // Cek jika user sebelumnya sudah memilih dark, atau jika belum ada pilihan default ke light
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();

        function toggleDarkMode() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>

    {{-- Global Search Script --}}
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
            /* Transisi halus saat ganti tema */
            body {
                @apply transition-colors duration-500 ease-in-out;
            }
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-slate-950 font-sans text-gray-900 dark:text-gray-100">
    
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Top Navbar / Header --}}
            <header class="h-16 flex items-center justify-end px-8">
                <button 
                    onclick="toggleDarkMode()" 
                    type="button"
                    class="p-2.5 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-sm hover:scale-110 active:scale-95 transition-all cursor-pointer"
                >
                    {{-- Ikon Moon (Tampil di Light Mode) --}}
                    <i class="ph-bold ph-moon text-blue-600 dark:hidden text-xl"></i>
                    {{-- Ikon Sun (Tampil di Dark Mode) --}}
                    <i class="ph-bold ph-sun text-yellow-400 hidden dark:block text-xl"></i>
                </button>
            </header>

            {{-- Main Content --}}
            <main class="flex-1 overflow-y-auto p-8 pt-2">
                @yield('content')
            </main>
        </div>

        {{-- Right Panel --}}
        @include('layouts.right-panel')
    </div>

</body>
</html>