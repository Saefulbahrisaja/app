@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6" x-data="{ tab: 'terjual' }">
    <h2 class="text-2xl font-bold mb-4">Laporan Inventori Barang</h2>

    <!-- Tabs -->
    <div class="mb-4 border-b border-gray-200">
        <nav class="flex space-x-4">
            <button @click="tab = 'terjual'" :class="tab === 'terjual' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'" class="px-3 py-2 font-medium">Barang Jadi Terjual</button>
            <button @click="tab = 'stok'" :class="tab === 'stok' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'" class="px-3 py-2 font-medium">Stok Barang Jadi</button>
            <button @click="tab = 'bahan'" :class="tab === 'bahan' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'" class="px-3 py-2 font-medium">Penggunaan Bahan Baku</button>
        </nav>
    </div>

    <!-- Filter Date -->
    <div class="mb-4 flex space-x-2">
        <input type="text" id="dateRange" class="border px-3 py-2 rounded" placeholder="Pilih Rentang Tanggal">
        <button id="filterBtn" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
        <button id="resetBtn" class="bg-gray-500 text-white px-4 py-2 rounded">Reset</button>
    </div>

    <!-- Tables -->
    <div x-show="tab === 'terjual'" x-cloak>
        <table class="min-w-full border border-gray-200" id="tableTerjual">
            <thead class="bg-gray-100">
                <tr>
                    <th>Nama Barang</th>
                    <th>Total Terjual</th>
                </tr>
            </thead>
        </table>
    </div>

    <div x-show="tab === 'stok'" x-cloak>
        <table class="min-w-full border border-gray-200" id="tableStok">
            <thead class="bg-gray-100">
                <tr>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                </tr>
            </thead>
        </table>
    </div>

    <div x-show="tab === 'bahan'" x-cloak>
        <table class="min-w-full border border-gray-200" id="tableBahan">
            <thead class="bg-gray-100">
                <tr>
                    <th>Nama Bahan</th>
                    <th>Stok Awal</th>
                    <th>Jumlah Digunakan</th>
                    <th>Sisa Stok</th>
                    <th>Satuan</th>
                    <th>Tanggal Penggunaan</th>
                </tr>
            </thead>
        </table>
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
        locale: { cancelLabel: 'Clear' }
    });

    $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
        start_date = picker.startDate.format('YYYY-MM-DD');
        end_date = picker.endDate.format('YYYY-MM-DD');
        $(this).val(start_date + ' s/d ' + end_date);
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
                ]
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
                ]
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
                ]
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
            let selectedTab = this.getAttribute('@click').split("'")[1]; // Ambil nama tab
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
