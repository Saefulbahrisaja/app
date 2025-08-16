@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-6 p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Laporan Penjualan</h2>

    <div class="flex flex-wrap gap-3 mb-4 items-center">
        <div>
            <label class="text-sm block">Start</label>
            <input type="date" id="start_date" value="{{ date('Y-m-01') }}" class="border rounded px-3 py-2">
        </div>
        <div>
            <label class="text-sm block">End</label>
            <input type="date" id="end_date" value="{{ date('Y-m-t') }}" class="border rounded px-3 py-2">
        </div>
        <div class="flex items-end gap-2">
            <button id="filterBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
            <a id="btnPdf" target="_blank" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Cetak PDF</a>
        </div>
    </div>

    <!-- Ringkasan -->
    <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="bg-green-50 p-4 rounded text-center">
            <div class="text-sm font-semibold">Total Pendapatan</div>
            <div id="summaryPendapatan" class="text-lg font-bold text-green-700">Rp 0</div>
        </div>
        <div class="bg-yellow-50 p-4 rounded text-center">
            <div class="text-sm font-semibold">Total Modal</div>
            <div id="summaryModal" class="text-lg font-bold text-yellow-700">Rp 0</div>
        </div>
        <div class="bg-blue-50 p-4 rounded text-center">
            <div class="text-sm font-semibold">Total Laba</div>
            <div id="summaryLaba" class="text-lg font-bold text-blue-700">Rp 0</div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table id="tablePenjualan" class="min-w-full border border-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2 border">Tanggal</th>
                    <th class="px-3 py-2 border">Produk</th>
                    <th class="px-3 py-2 border">Jumlah</th>
                    <th class="px-3 py-2 border">Harga</th>
                    <th class="px-3 py-2 border">Subtotal</th>
                    <th class="px-3 py-2 border">Modal/unit</th>
                    <th class="px-3 py-2 border">Laba Item</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(function(){
    function formatRupiah(n) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(n || 0);
    }

    let table = $('#tablePenjualan').DataTable({
        ajax: {
            url: "{{ route('laporan.penjualan.data') }}",
            data: function(d){
                d.start_date = $('#start_date').val();
                d.end_date   = $('#end_date').val();
            },
            dataSrc: function(json){
                // update ringkasan
                $('#summaryPendapatan').text(formatRupiah(json.summary.total_pendapatan));
                $('#summaryModal').text(formatRupiah(json.summary.total_modal));
                $('#summaryLaba').text(formatRupiah(json.summary.total_laba));
                return json.data;
            }
        },
        columns: [
            { data: 'tanggal' },
            { data: 'produk' },
            { data: 'jumlah' },
            { data: 'harga', render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
            { data: 'subtotal', render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
            { data: 'modal_per_unit', render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
            { data: 'laba_item', render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
        ],
        order: [[0, 'desc']],
        pageLength: 25,
    });

    $('#filterBtn').on('click', function(){
        table.ajax.reload();
        const s = $('#start_date').val();
        const e = $('#end_date').val();
        $('#btnPdf').attr('href', "{{ route('laporan.penjualan.pdf') }}" + '?start_date=' + s + '&end_date=' + e);
    });

    // set default pdf link
    $('#btnPdf').attr('href', "{{ route('laporan.penjualan.pdf') }}" + '?start_date=' + $('#start_date').val() + '&end_date=' + $('#end_date').val());
});
</script>
@endpush
