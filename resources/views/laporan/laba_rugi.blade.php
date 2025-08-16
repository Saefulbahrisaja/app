@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-6 p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Laporan Laba Rugi</h2>

    <!-- Filter -->
    <div class="flex flex-wrap gap-3 mb-4">
        <input type="date" id="start_date" value="{{ date('Y-m-01') }}" class="border rounded px-3 py-2">
        <input type="date" id="end_date" value="{{ date('Y-m-t') }}" class="border rounded px-3 py-2">
        <button id="filter" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
        <a id="btn-pdf" href="#" target="_blank" 
           class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Cetak PDF</a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="labaRugiTable" class="min-w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border text-left">Pendapatan</th>
                    <th class="px-4 py-2 border text-left">HPP</th>
                    <th class="px-4 py-2 border text-left">Laba Kotor</th>
                    <th class="px-4 py-2 border text-left">Biaya Operasional</th>
                    <th class="px-4 py-2 border text-left">Laba Bersih</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
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
            emptyTable: "Tidak ada data untuk periode ini"
        }
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
