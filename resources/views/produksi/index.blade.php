@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto mt-4 p-6 bg-white rounded shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-700">Catatan Produksi</h2>

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

    <div class="flex justify-between items-center mb-4">
        <form method="GET" class="flex gap-2 items-center">
            <label class="text-sm text-gray-600">Dari:</label>
            <input type="date" name="start_date" value="{{ $start->format('Y-m-d') }}" class="border border-gray-300 p-2 rounded">
            <label class="text-sm text-gray-600">Sampai:</label>
            <input type="date" name="end_date" value="{{ $end->format('Y-m-d') }}" class="border border-gray-300 p-2 rounded">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Filter</button>
        </form>

        <div x-data="{ open: false }" class="relative z-10">
            <button @click="open = true"
                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700">
                Tambah Produksi
            </button>
            <!-- Overlay -->
            <div x-show="open"
                class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50"
                x-cloak>
                <!-- Modal Box -->
                <div @click.away="open = false" class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">Tambah Produksi</h3>

                    <form action="{{ route('production.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-600">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" class="w-full border-gray-300 rounded-md shadow-sm mt-1 px-4 py-2 text-base">
                        </div>

                        <div>
                            <label for="produk" class="block text-sm font-medium text-gray-600 mt-4">Nama Produk</label>
                            <input type="text" name="produk" id="produk" class="w-full border-gray-300 rounded-xl shadow-sm mt-1 px-4 py-2 text-base">
                        </div>

                        <div>
                            <label for="jumlah_produksi" class="block text-sm font-medium text-gray-600 mt-4">Jumlah Produksi</label>
                            <input type="number" name="jumlah_produksi" id="jumlah_produksi" class="w-full border-gray-300 rounded-md shadow-sm mt-1 px-4 py-2 text-base">
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-600 mb-2">Pilih Bahan Baku</label>
                            <div class="space-y-3">
                               @foreach($bahanBaku as $item)
                                <div class="flex items-center gap-3">
                                    <span class="text-sm w-1/2">
                                        {{ $item->nama_barang }} <br>
                                        Sisa: 
                                        <span class="{{ $item->stok == 0 ? 'text-red-600 font-semibold' : 'text-xs text-green-600 font-semibold' }}">
                                            {{ $item->stok }} {{ $item->satuan }}
                                        </span>
                                        @if ($item->stok == 0)
                                            <span class="text-xs text-red-500 italic">Stok habis!</span>
                                        @endif
                                    </span>

                                    <div class="relative w-1/2">
                                        <span class="absolute inset-y-0 left-0 pl-2 flex items-center text-gray-500 text-sm"></span>
                                        <input
                                            type="number"
                                            name="bahan_baku[{{ $item->id }}]"
                                            placeholder="Qty"
                                            class="w-full border-gray-300 rounded-md shadow-sm pl-10 py-2 text-base"
                                        >
                                    </div>

                                    <span class="text-sm">{{ $item->satuan }}</span>
                                </div>
                            @endforeach
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="biaya_tenaga_kerja" class="block text-sm font-medium text-gray-600">Biaya Tenaga Kerja</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">Rp</span>
                                <input
                                    type="number"
                                    name="biaya_tenaga_kerja"
                                    id="biaya_tenaga_kerja"
                                    class="w-full border-gray-300 rounded-md shadow-sm pl-10 py-2 text-base"
                                >
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="biaya_overhead" class="block text-sm font-medium text-gray-600">Biaya Overhead</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">Rp</span>
                                <input
                                    type="number"
                                    name="biaya_overhead"
                                    id="biaya_overhead"
                                    class="w-full border-gray-300 rounded-md shadow-sm pl-10 py-2 text-base"
                                >
                            </div>
                        </div>


                        <div class="flex justify-end gap-2 pt-4">
                            <button type="button" @click="open = false"
                                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Batal</button>
                            <button type="submit"
                                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
       
        
    </div>

    <div class="overflow-x-auto rounded">
        <table id="produksiTable" class="min-w-full table-auto mb-0">
            <thead class="bg-gray-100">
            <tr>
                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Tanggal Produksi</th>
                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Nama Produk</th>
                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Jumlah Produksi</th>
                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Bahan Baku</th>
                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Total Biaya</th>
                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">HPP/pcs</th>
                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Harga Jual/pcs</th>
                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Margin/pcs</th>
                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Profit %</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($productions as $prod)
            <tr class="border-b">
                <td class="px-3 py-2 ">{{ $prod->tanggal }}</td>
                <td class="px-3 py-2 ">{{ $prod->produk }}</td>
                <td class="px-3 py-2 ">{{ $prod->jumlah_produksi }}</td>
                <td class="px-3 py-2 text-xs">
                <ul class="list-disc pl-4">
                    @foreach ($prod->bahanBaku as $bahan)
                    <li>{{ $bahan->nama_barang }}: {{ $bahan->pivot->jumlah }} {{ $bahan->satuan }}</li>
                    @endforeach
                </ul>
                </td>
                <td class="px-3 py-2 ">
                Rp{{ number_format($prod->total_biaya_bahan + $prod->biaya_tenaga_kerja + $prod->biaya_overhead, 0, ',', '.') }}
                </td>
                <td class="px-3 py-2">
                Rp{{ number_format($prod->hpp_per_unit ?? 0, 0, ',', '.') }}
                </td>
                <td class="px-3 py-2">Rp{{ number_format($prod->harga_jual, 0, ',', '.') }}</td>
                <td class="px-3 py-2">Rp{{ number_format($prod->margin, 0, ',', '.') }}</td>
                <td class="px-3 py-2">{{ $prod->profit_percent }}%</td>
            </tr>
            @endforeach
            </tbody>
        </table>
       
    </div>
</div>


@endsection