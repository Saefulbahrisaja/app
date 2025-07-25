@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto mt-4 p-6 bg-white rounded shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-700">Data Arus Kas</h2>
    {{-- Pesan Error atau Success --}}
    @if (session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<div class="mt-6 border-t pt-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-700 font-semibold">
            <div class="bg-green-50 p-4 rounded">
                <p>Total Kas Masuk:</p>
                <p class="text-green-700 text-lg">Rp{{ number_format($totalMasuk, 0, ',', '.') }}</p>
            </div>

            <div class="bg-red-50 p-4 rounded">
                <p>Total Kas Keluar:</p>
                <p class="text-red-600 text-lg">(Rp{{ number_format($totalKeluar, 0, ',', '.') }})</p>
            </div>

            <div class="bg-blue-50 p-4 rounded">
                <p>Saldo Akhir Kas:</p>
                <p class="text-blue-700 text-lg font-bold">
                    Rp{{ number_format($saldo, 0, ',', '.') }}
                </p>
            </div>
        </div>
        <br>
        <h4 class="font-bold mb-2 text-gray-700">Rincian Transaksi Kas</h4>
    <div class="overflow-x-auto rounded">
        <table id="produksiTable" class="min-w-full table-auto mb-0">
            <thead class="bg-gray-100">
            <tr>
                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Tanggal Transaksi</th>
                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Jenis</th>
                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Kategori</th>
                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Jumlah</th>
                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Keterangan</th>
            </tr>
            </thead>
            <tbody>
               
            @foreach ($data as $row)
            <tr class="border-b">
                <td class="px-3 py-2">{{ $row->tanggal->format('d M Y') }}</td>
                <td class="px-3 py-2">{{ ucfirst($row->type) }}</td>
                <td class="px-3 py-2">{{ $row->kategori }}</td>

                <td class="px-3 py-2">
                    @if ($row->type === 'keluar')
                        <span class="text-red-600 font-semibold">(Rp{{ number_format($row->jumlah, 0, ',', '.') }})</span>
                    @else
                        <span class="text-green-700 font-semibold">Rp{{ number_format($row->jumlah, 0, ',', '.') }}</span>
                    @endif
                </td>

                <td class="px-3 py-2">{{ $row->keterangan }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        
    </div>
       
    </div>
</div>
@endsection

