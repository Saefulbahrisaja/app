@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Laporan Laba Rugi</h1>
        <p class="mt-2 text-sm text-gray-600">Analisis kinerja keuangan perusahaan Anda</p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Filter Periode</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <div class="relative">
                    <input type="date" id="start_date" value="{{ date('Y-m-01') }}" 
                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                <div class="relative">
                    <input type="date" id="end_date" value="{{ date('Y-m-t') }}" 
                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="flex space-x-2">
                <button id="filter" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filter
                </button>
                
                <a id="btn-pdf" href="#" target="_blank" 
                   class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Cetak PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Ringkasan Keuangan -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-r from-green-400 to-green-600 rounded-lg p-6 text-white shadow-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-green-100">Pendapatan</div>
                    <div id="pendapatan" class="text-2xl font-bold">Rp 0</div>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-red-400 to-red-600 rounded-lg p-6 text-white shadow-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-red-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 012-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-red-100">HPP</div>
                    <div id="hpp" class="text-2xl font-bold">Rp 0</div>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-lg p-6 text-white shadow-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-yellow-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-yellow-100">Laba Kotor</div>
                    <div id="laba_kotor" class="text-2xl font-bold">Rp 0</div>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-400 to-blue-600 rounded-lg p-6 text-white shadow-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-blue-100">Laba Bersih</div>
                    <div id="laba_bersih" class="text-2xl font-bold">Rp 0</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Laba Rugi -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Detail Laba Rugi</h2>
            <p class="text-sm text-gray-600">Ringkasan kinerja keuangan berdasarkan periode yang dipilih</p>
        </div>
        
        <div class="overflow-x-auto">
            <table id="labaRugiTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendapatan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">HPP</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laba Kotor</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Biaya Operasional</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laba Bersih</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- DataTables will populate this -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script>
$(document).ready(function(){
    let table = $('#labaRugiTable').DataTable({
        processing: true,
        serverSide: false,
        paging: false,
        searching: false,
        info: false,
        ajax: {
            url: "{{ route('laba_rugi.data') }}",
            data: function (d) {
                d.start_date = $('#start_date').val();
                d.end_date   = $('#end_date').val();
            },
            dataSrc: function(json) {
                // Update summary cards
                if (json.data && json.data.length > 0) {
                    const data = json.data[0];
                    $('#pendapatan').text('Rp ' + new Intl.NumberFormat('id-ID').format(data.pendapatan || 0));
                    $('#hpp').text('Rp ' + new Intl.NumberFormat('id-ID').format(data.hpp || 0));
                    $('#laba_kotor').text('Rp ' + new Intl.NumberFormat('id-ID').format(data.laba_kotor || 0));
                    $('#laba_bersih').text('Rp ' + new Intl.NumberFormat('id-ID').format(data.laba_bersih || 0));
                }
                return json.data;
            }
        },
        columns: [
            { data: 'pendapatan', render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
            { data: 'hpp', render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
            { data: 'laba_kotor', render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
            { data: 'biaya_operasional', render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
            { data: 'laba_bersih', render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ') }
        ],
        language: {
            emptyTable: "Tidak ada data untuk periode ini",
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        },
        responsive: true
    });

    $('#filter').click(function(){
        table.ajax.reload();
        let start = $('#start_date').val();
        let end   = $('#end_date').val();
        $('#btn-pdf').attr('href', "{{ route('laba_rugi.pdf') }}?start_date=" + start + "&end_date=" + end);
    });

    // Set default PDF link saat load pertama
    $('#btn-pdf').attr('href', "{{ route('laba_rugi.pdf') }}?start_date=" + $('#start_date').val() + "&end_date=" + $('#end_date').val());
});
</script>
@endpush
