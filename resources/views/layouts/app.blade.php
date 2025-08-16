<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kas Pro</title>
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
            border-radius: 0.5rem;
            padding: 2rem;
            min-width: 300px;
            max-width: 90vw;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>
    <style>
        .loader-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
            transition: opacity 0.5s ease-out;
        }
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #6366f1;
            border-radius: 50%;
            width: 3rem; height: 3rem;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg);}
            100% { transform: rotate(360deg);}
        }
        .hidden {
            opacity: 0;
            visibility: hidden;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 text-sm flex min-h-screen">
    <!-- Loader Overlay -->
    <div id="loader-overlay" class="loader-overlay">
        <div class="loader"></div>
    </div>
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md p-4 space-y-6">
        <!-- Login Info Section -->
        <div class="bg-gray-100 p-4 rounded-lg mb-4">
            <p class="text-sm text-gray-600"><strong>KAS PRO APP</strong></p>
            <p class="text-xs text-gray-400">Role: <strong></strong></p>
            <p class="text-xs text-gray-400">Email: <strong></strong></p>
        </div>
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Menu</h2>
        <nav class="space-y-4">
            <!-- Dashboard Link -->
            <a href="{{ route('dashboard.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-200 {{ request()->routeIs('absensi.index') ? 'bg-gray-200 font-semibold' : '' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                Dashboard
            </a>
            <!-- Data User Link -->
            <a href="{{ route('inventories.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-200 {{ request()->routeIs('inventori.index') ? 'bg-gray-200 font-semibold' : '' }}">
                <i data-lucide="box" class="w-4 h-4"></i>
                Data Inventori
            </a>
            <a href="{{ route('cash-flows.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-200 {{ request()->routeIs('arus-kas.index') ? 'bg-gray-200 font-semibold' : '' }}">
                <i data-lucide="credit-card" class="w-4 h-4"></i>
                Data Arus Kas
            </a>
            <a href="{{ route('production.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-200 {{ request()->routeIs('arus-kas.index') ? 'bg-gray-200 font-semibold' : '' }}">
                <i data-lucide="factory" class="w-4 h-4"></i>
                Produksi
            </a>

            <!-- Collapsible Report Menu -->
            <div x-data="{ open: {{ request()->is('laporan/*') ? 'true' : 'false' }} }" class="border-t pt-2">
                <button @click="open = !open" class="flex justify-between items-center w-full px-4 py-2 rounded-lg hover:bg-gray-200">
                    <span class="flex items-center gap-2">
                        <i data-lucide="bar-chart" class="w-4 h-4"></i>
                        Laporan
                    </span>
                    <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" class="mt-2 space-y-2 pl-6" x-cloak>
                    <a href="{{ route('laporan.kas') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-200 {{ request()->routeIs('laporan.kehadiran') ? 'bg-gray-200 font-semibold' : '' }}">
                        <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                        Laporan Kas (CashFlow)
                    </a>
                    <a href="{{ route('laporan.inventori') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-200 {{ request()->routeIs('laporan.kpi') ? 'bg-gray-200 font-semibold' : '' }}">
                        <i data-lucide="boxes" class="w-4 h-4"></i>
                        Laporan Inventori
                    </a>
                   
                    <a href="{{ route('laba_rugi.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-200 {{ request()->routeIs('laporan.kpi') ? 'bg-gray-200 font-semibold' : '' }}">
                        <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                        Laporan Laba/Rugi
                    </a>
                </div>
            </div>
            <!-- Collapsible Setting Menu -->
           
            <!-- Logout Menu Item -->
            <form action="" method="POST" class="border-t pt-4">
                @csrf
                <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 rounded-lg hover:bg-gray-200 text-red-600 font-semibold transition duration-200">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    Logout
                </button>
            </form>
        </nav>
    </aside>
    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <div class="flex-1 overflow-y-auto p-6">
            <div class="bg-white shadow-lg rounded-lg p-6">
                @yield('content')
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
