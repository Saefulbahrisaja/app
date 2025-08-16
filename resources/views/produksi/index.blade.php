@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto mt-6 p-8 bg-white rounded-xl shadow-2xl">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold text-gray-800 flex items-center">
            <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Catatan Produksi
        </h2>
        
        <div x-data="{ open: false }" class="relative">
            <button @click="open = true"
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg shadow-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Produksi
            </button>
            
            <!-- Modal -->
            <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
                    <div class="flex items-center justify-between p-6 border-b">
                        <h3 class="text-xl font-bold text-gray-800">Tambah Produksi Baru</h3>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <form action="{{ route('production.store') }}" method="POST" class="p-6 space-y-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Produksi</label>
                                <input type="date" name="tanggal" id="tanggal" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="produk" class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk</label>
                                <input type="text" name="produk" id="produk" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Masukkan nama produk">
                            </div>
                        </div>
                        
                        <div>
                            <label for="jumlah_produksi" class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Produksi</label>
                            <input type="number" name="jumlah_produksi" id="jumlah_produksi" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0">
                        </div>
                        
                        <div class="border-t pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Bahan Baku</h4>
                            <div class="space-y-4">
                                @foreach($bahanBaku as $item)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">{{ $item->nama_barang }}</p>
                                        <p class="text-sm text-gray-600">
                                            Stok: 
                                            <span class="{{ $item->stok == 0 ? 'text-red-600 font-bold' : 'text-green-600' }}">
                                                {{ $item->stok }} {{ $item->satuan }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="w-32">
                                        <input type="number" name="bahan_baku[{{ $item->id }}]" placeholder="Qty" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-center">
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">{{ $item->satuan }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t">
                            <div>
                                <label for="biaya_tenaga_kerja" class="block text-sm font-semibold text-gray-700 mb-2">Biaya Tenaga Kerja</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                    <input type="number" name="biaya_tenaga_kerja" id="biaya_tenaga_kerja" 
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="0">
                                </div>
                            </div>
                            
                            <div>
                                <label for="biaya_overhead" class="block text-sm font-semibold text-gray-700 mb-2">Biaya Overhead</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                    <input type="number" name="biaya_overhead" id="biaya_overhead" 
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-6 border-t">
                            <button type="button" @click="open = false"
                                class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Simpan Produksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">
            <div class="flex">
                <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg">
            <div class="flex">
                <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-6 mb-6">
        <form method="GET" class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <label class="text-sm font-medium text-gray-700 mr-2">Periode:</label>
                    <input type="date" name="start_date" value="{{ $start->format('Y-m-d') }}" 
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <span class="text-gray-500">s/d</span>
                <input type="date" name="end_date" value="{{ $end->format('Y-m-d') }}" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" 
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Terapkan Filter
            </button>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table id="produksiTable" class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Produk</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Jumlah</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Bahan Baku</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Total Biaya</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">HPP/pcs</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Harga Jual</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Margin</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Profit %</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($productions as $prod)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $prod->tanggal }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $prod->produk }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($prod->jumlah_produksi) }} pcs</td>
                        <td class="px-6 py-4">
                            <ul class="text-sm text-gray-600 space-y-1">
                                @foreach ($prod->bahanBaku as $bahan)
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                    {{ $bahan->nama_barang }}: {{ $bahan->pivot->jumlah }} {{ $bahan->satuan }}
                                </li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            Rp{{ number_format($prod->total_biaya_bahan + $prod->biaya_tenaga_kerja + $prod->biaya_overhead, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            Rp{{ number_format($prod->hpp_per_unit ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            Rp{{ number_format($prod->harga_jual, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-green-600">
                            Rp{{ number_format($prod->margin, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $prod->profit_percent }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Add any additional JavaScript for enhanced functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any required JavaScript libraries
    });
</script>
@endpush

@endsection
