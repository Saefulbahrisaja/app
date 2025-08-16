<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartKas</title>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Heroicons CDN -->
    <script src="https://unpkg.com/@heroicons/vue@2.0.13/dist/heroicons.min.js"></script>
    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- DataTables CSS & JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- Modal Styles (Tailwind) -->
    <style>
        .modal-bg {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: #fff;
            border-radius: 0.75rem;
            padding: 2rem;
            min-width: 300px;
            max-width: 90vw;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
    </style>
    <style>
        .loader-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
            transition: opacity 0.5s ease-out;
        }
        .loader {
            width: 48px;
            height: 48px;
            border: 5px solid #FFF;
            border-bottom-color: transparent;
            border-radius: 50%;
            animation: rotation 1s linear infinite;
        }
        @keyframes rotation {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .hidden {
            opacity: 0;
            visibility: hidden;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 text-slate-800 text-sm flex min-h-screen">
    <!-- Loader Overlay -->
    <div id="loader-overlay" class="loader-overlay">
        <div class="loader"></div>
    </div>
    
    <!-- Sidebar -->
    <aside class="w-72 bg-white shadow-xl border-r border-slate-200 flex flex-col">
        <!-- Header Section with Logo -->
        <div class="p-6 border-b border-slate-200">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4 rounded-xl text-white">
                <!-- Logo Section -->
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                        <img class="w-8 h-8 rounded" src="{{ asset('img/logo_sijimat.jpg') }}" width="60" alt="Logo">
                    </div>
                    <div>
                        <h1 class="text-lg font-bold">SmartKas</h1>
                        <p class="text-xs opacity-90">Kas Sijimat</p>
                    </div>
                </div>
                
                <div class="mt-2 space-y-1">
                    <p class="text-xs opacity-90">
                        <span class="font-medium">Role:</span> {{ Auth::user()->role ?? '-' }}
                    </p>
                    <p class="text-xs opacity-90">
                        <span class="font-medium">Email:</span> {{ Auth::user()->email ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <div class="space-y-1">
                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 group {{ request()->routeIs('dashboard.index') ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>
                
                <a href="{{ route('inventories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 group {{ request()->routeIs('inventories.index') ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                    <i data-lucide="box" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm font-medium">Data Inventori</span>
                </a>
                
                <a href="{{ route('cash-flows.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 group {{ request()->routeIs('cash-flows.index') ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                    <i data-lucide="credit-card" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm font-medium">Data Arus Kas</span>
                </a>
                
                <a href="{{ route('production.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 group {{ request()->routeIs('production.index') ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                    <i data-lucide="factory" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm font-medium">Produksi</span>
                </a>
                
                <a href="{{ route('markup.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 group {{ request()->routeIs('markup.index') ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                    <i data-lucide="cog" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm font-medium">Setup Margin</span>
                </a>
            </div>

            <!-- Reports Section -->
            <div class="pt-4 mt-4 border-t border-slate-200">
                <div x-data="{ open: {{ request()->is('laporan/*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="flex justify-between items-center w-full px-4 py-3 rounded-lg text-slate-700 hover:bg-slate-50 transition-all duration-200 group">
                        <div class="flex items-center gap-3">
                            <i data-lucide="bar-chart" class="w-5 h-5"></i>
                            <span class="text-sm font-medium">Laporan</span>
                        </div>
                        <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" class="mt-2 space-y-1 pl-4" x-cloak>
                        <a href="{{ route('laporan.kas') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-sm {{ request()->routeIs('laporan.kas') ? 'bg-indigo-50 text-indigo-700 font-medium' : '' }}">
                            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                            Laporan Kas (CashFlow)
                        </a>
                        <a href="{{ route('laporan.inventori') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-sm {{ request()->routeIs('laporan.inventori') ? 'bg-indigo-50 text-indigo-700 font-medium' : '' }}">
                            <i data-lucide="boxes" class="w-4 h-4"></i>
                            Laporan Inventori
                        </a>
                        <a href="{{ route('laporan.penjualan') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-sm {{ request()->routeIs('laporan.penjualan') ? 'bg-indigo-50 text-indigo-700 font-medium' : '' }}">
                            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                            Laporan Penjualan
                        </a>
                        <a href="{{ route('laba_rugi.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-sm {{ request()->routeIs('laba_rugi.index') ? 'bg-indigo-50 text-indigo-700 font-medium' : '' }}">
                            <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                            Laporan Laba/Rugi
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Logout Section -->
        <div class="p-4 border-t border-slate-200">
            @auth
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 rounded-lg text-slate-700 hover:bg-red-50 hover:text-red-600 transition-all duration-200 group">
                    <i data-lucide="log-out" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm font-medium">Logout</span>
                </button>
            </form>
            @endauth
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <div class="flex-1 overflow-y-auto p-8">
            <div class="bg-white shadow-xl rounded-2xl border border-slate-200 overflow-hidden">
                <div class="p-6">
                    @yield('content')
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script>
        window.addEventListener('load', () => {
            const loaderOverlay = document.getElementById('loader-overlay');
            loaderOverlay.classList.add('hidden');
        });
    </script>
    <script>
        lucide.createIcons();
    </script>
    {{-- DataTables JS & CSS --}}
    <script>
        $(document).ready(function() {
            $('#produksiTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
