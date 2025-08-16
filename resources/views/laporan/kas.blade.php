@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-6 p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Laporan Kas</h2>

    <!-- Form Filter -->
    <form method="GET" action="{{ route('laporan.kas') }}" class="mb-4 flex gap-2">
        <input type="date" name="start_date" value="{{ $start }}" class="border rounded px-2 py-1">
        <input type="date" name="end_date" value="{{ $end }}" class="border rounded px-2 py-1">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
        <a href="{{ route('laporan.kas.export', ['start_date' => $start, 'end_date' => $end]) }}"
           class="bg-green-600 text-white px-4 py-2 rounded">Export Excel</a>
    </form>

    <!-- Ringkasan -->
    <div class="grid grid-cols-3 gap-4 text-sm font-semibold mb-4">
        <div class="bg-green-50 p-3 rounded">
            Kas Masuk: <span class="text-green-700">Rp{{ number_format($totalMasuk, 0, ',', '.') }}</span>
        </div>
        <div class="bg-red-50 p-3 rounded">
            Kas Keluar: <span class="text-red-600">Rp{{ number_format($totalKeluar, 0, ',', '.') }}</span>
        </div>
        <div class="bg-blue-50 p-3 rounded">
            Saldo: <span class="text-blue-700">Rp{{ number_format($saldo, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Tabel -->
    <table class="min-w-full table-auto border">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-3 py-2 border">Tanggal</th>
                <th class="px-3 py-2 border">Jenis</th>
                <th class="px-3 py-2 border">Kategori</th>
                <th class="px-3 py-2 border">Jumlah</th>
                <th class="px-3 py-2 border">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    <td class="px-3 py-2 border">{{ $row->tanggal->format('d M Y') }}</td>
                    <td class="px-3 py-2 border">{{ ucfirst($row->type) }}</td>
                    <td class="px-3 py-2 border">{{ $row->kategori }}</td>
                    <td class="px-3 py-2 border {{ $row->type === 'keluar' ? 'text-red-600' : 'text-green-700' }}">
                        {{ $row->type === 'keluar' ? '(Rp'.number_format($row->jumlah,0,',','.') .')' : 'Rp'.number_format($row->jumlah,0,',','.') }}
                    </td>
                    <td class="px-3 py-2 border">{{ $row->keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
