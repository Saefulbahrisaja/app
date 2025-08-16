@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Laporan Inventori Barang</h1>
        <p class="mt-2 text-sm text-gray-600">Kelola dan pantau inventori barang Anda dengan mudah</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200" x-data="{ tab: 'terjual' }">
        <!-- Tabs -->
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button @click="tab = 'terjual'" 
                        :class="tab === 'terjual' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Barang Jadi Terjual
                    </div>
                </button>
                
                <button @click="tab = 'stok'" 
                        :class="tab === 'stok' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Stok Barang Jadi
                    </div>
                </button>
                
                <button @click="tab = 'bahan'" 
                        :class="tab === 'bahan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Penggunaan Bahan Baku
                    </div>
                </button>
            </nav>
        </div>

        <!-- Filter Section -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-3 sm:space-y-0">
                <div class="flex-1">
                    <label for="dateRange" class="block text-sm font-medium text-gray-700 mb-1">Rentang Tanggal</label>
                    <div class="relative">
                        <input type="text" id="dateRange" 
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                               placeholder="Pilih rentang tanggal">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    <button id="filterBtn" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filter
                    </button>
                    
                    <button id="resetBtn" 
                            class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Barang Jadi Terjual -->
            <div x-show="tab === 'terjual'" x-cloak>
                <div class="overflow-hidden rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200" id="tableTerjual">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Terjual</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- DataTables will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Stok Barang Jadi -->
            <div x-show="tab === 'stok'" x-cloak>
                <div class="overflow-hidden rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200" id="tableStok">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- DataTables will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Penggunaan Bahan Baku -->
            <div x-show="tab === 'bahan'" x-cloak>
                <div class="overflow-hidden rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200" id="tableBahan">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Bahan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Awal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Digunakan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Stok</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Penggunaan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- DataTables will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- Date Range Picker -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

<script>
$(document).ready(function() {
    let start_date = '';
    let end_date = '';

    // Date range picker
    $('#dateRange').daterangepicker({
        autoUpdateInput: false,
        locale: { 
            cancelLabel: 'Clear',
            format: 'DD/MM/YYYY'
        },
        ranges: {
           'Hari Ini': [moment(), moment()],
           'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
           '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
           'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
           'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
        start_date = picker.startDate.format('YYYY-MM-DD');
        end_date = picker.endDate.format('YYYY-MM-DD');
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    $('#dateRange').on('cancel.daterangepicker', function() {
        $(this).val('');
        start_date = '';
        end_date = '';
    });

    let tableTerjual = null;
    let tableStok = null;
    let tableBahan = null;

    function loadTerjual() {
        if (!tableTerjual) {
            tableTerjual = $('#tableTerjual').DataTable({
                ajax: {
                    url: "{{ route('laporan.inventori.terjual') }}",
                    data: function(d) {
                        d.start_date = start_date;
                        d.end_date = end_date;
                    }
                },
                columns: [
                    { data: 'nama_barang' },
                    { data: 'total_terjual' }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                responsive: true,
                pageLength: 10
            });
        } else {
            tableTerjual.ajax.reload();
        }
    }

    function loadStok() {
        if (!tableStok) {
            tableStok = $('#tableStok').DataTable({
                ajax: "{{ route('laporan.inventori.stok') }}",
                columns: [
                    { data: 'nama_barang' },
                    { data: 'stok' },
                    { data: 'satuan' }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                responsive: true,
                pageLength: 10
            });
        }
    }

    function loadBahan() {
        if (!tableBahan) {
            tableBahan = $('#tableBahan').DataTable({
                ajax: {
                    url: "{{ route('laporan.inventori.bahan') }}",
                    data: function(d) {
                        d.start_date = start_date;
                        d.end_date = end_date;
                    }
                },
                columns: [
                    { data: 'nama_bahan' },
                    { data: 'stok_awal' },
                    { data: 'jumlah_digunakan' },
                    { data: 'sisa_stok' },
                    { data: 'satuan' },
                    { data: 'tanggal_penggunaan' }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                responsive: true,
                pageLength: 10
            });
        } else {
            tableBahan.ajax.reload();
        }
    }

    // Default load tab pertama
    loadTerjual();

    // Event klik tab
    document.querySelectorAll('nav button').forEach(btn => {
        btn.addEventListener('click', function() {
            let selectedTab = this.getAttribute('@click').split("'")[1];
            if (selectedTab === 'terjual') loadTerjual();
            if (selectedTab === 'stok') loadStok();
            if (selectedTab === 'bahan') loadBahan();
        });
    });

    // Filter button
    $('#filterBtn').click(function() {
        if (tableTerjual) tableTerjual.ajax.reload();
        if (tableBahan) tableBahan.ajax.reload();
    });

    // Reset filter
    $('#resetBtn').click(function() {
        start_date = '';
        end_date = '';
        $('#dateRange').val('');
        if (tableTerjual) tableTerjual.ajax.reload();
        if (tableBahan) tableBahan.ajax.reload();
    });
});
</script>
@endpush
