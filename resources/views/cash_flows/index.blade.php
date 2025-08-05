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
<div class="flex justify-between items-center mb-4">
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
    
    <div x-data="{ open: false }" class="relative z-10">
        <button @click="open = true"
            class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700">
            Tambah Transaksi Kas
        </button>
        <!-- Overlay -->
        <div x-show="open"
            class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50"
            x-cloak>
            <!-- Modal Box -->
            <div @click.away="open = false" class="bg-white w-full max-w-lg rounded-lg shadow-lg flex flex-col max-h-[90vh]">
                <h3 class="text-lg font-semibold p-6 pb-4 text-gray-700 border-b">Tambah Transaksi Kas</h3>
                <form action="{{ route('cash-flows.store') }}" method="POST" class="flex-1 overflow-y-auto p-6 pt-4 space-y-4">
                    @csrf
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-600">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" id="tanggal" class="w-full border-gray-300 rounded-md shadow-sm mt-1 px-4 py-2 text-base">
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-600">Jenis Transaksi</label>
                        <select name="type" id="type" class="w-full border-gray-300 rounded-md shadow-sm mt-1 px-4 py-2 text-base">
                            <option value="masuk">Kas Masuk</option>
                            <option value="keluar">Kas Keluar</option>
                        </select>
                    </div>

                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-600">Kategori</label>
                        <input type="text" name="kategori" id="kategori" class="w-full border-gray-300 rounded-md shadow-sm mt-1 px-4 py-2 text-base" placeholder="Contoh: Penjualan, Gaji, Operasional">
                    </div>

                    <div>
                        <label for="jumlah" class="block text-sm font-medium text-gray-600">Jumlah</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">Rp</span>
                            <input type="number" name="jumlah" id="jumlah" class="w-full border-gray-300 rounded-md shadow-sm pl-10 py-2 text-base">
                        </div>
                    </div>

                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-600">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3" class="w-full border-gray-300 rounded-md shadow-sm mt-1 px-4 py-2 text-base" placeholder="Deskripsi transaksi..."></textarea>
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

