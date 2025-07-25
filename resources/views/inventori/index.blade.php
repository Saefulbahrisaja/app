@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6" x-data="{ open: false }">

    <!-- Tombol Tambah Barang -->
    <button @click="open = true" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4">
        + Tambah Barang
    </button>

    <!-- Modal Tambah Barang -->
    <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="open = false" class="bg-white rounded shadow-lg max-w-md w-full p-6">
            <h2 class="text-xl font-semibold mb-4">Tambah Barang</h2>
            <form action="{{ route('inventories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Nama Barang</label>
                    <input type="text" name="nama_barang" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Jenis</label>
                    <select name="jenis" class="w-full border rounded px-3 py-2" required>
                        <option value="bahan_baku">Bahan Baku</option>
                        <option value="barang_jadi">Barang Jadi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Stok</label>
                    <input type="number" name="stok" class="w-full border rounded px-3 py-2" min="0" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Satuan</label>
                    <input type="text" name="satuan" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Harga Satuan</label>
                    <input type="number" step="0.01" name="harga_satuan" class="w-full border rounded px-3 py-2">
                </div>
                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" @click="open = false" class="bg-gray-300 px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Bahan Baku -->
    <div class="bg-white rounded shadow p-4 mb-6">
        <h3 class="text-lg font-semibold mb-4">Bahan Baku</h3>
        <div class="overflow-x-auto">
            <table id="tableBahanBaku" class="min-w-full text-sm border border-gray-200">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2">Nama bahan baku</th>
                        <th class="px-4 py-2">Stok</th>
                        <th class="px-4 py-2">Satuan</th>
                        <th class="px-4 py-2">Harga Satuan</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($bahanBaku as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border-b">{{ $item->nama_barang }}</td>
                        <td class="px-4 py-2 border-b">{{ $item->stok }}</td>
                        <td class="px-4 py-2 border-b">{{ $item->satuan }}</td>
                        <td class="px-4 py-2 border-b">Rp{{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 border-b space-x-1">
                            <a href="{{ route('inventories.edit', $item->id) }}" class="bg-yellow-400 text-white px-3 py-1 rounded text-xs hover:bg-yellow-500">Edit</a>
                            <form action="" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin hapus?')" class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabel Barang Jadi -->
    <div class="bg-white rounded shadow p-4 mb-6">
        <h3 class="text-lg font-semibold mb-4">Barang Hasil Produksi</h3>
        <div class="overflow-x-auto">
            <table id="tableBarangJadi" class="min-w-full text-sm border border-gray-200">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2">Nama Produk</th>
                        <th class="px-4 py-2">Stok</th>
                        <th class="px-4 py-2">Satuan</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($barangJadi as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border-b">{{ $item->nama_barang }}</td>
                        <td class="px-4 py-2 border-b">{{ $item->stok }}</td>
                        <td class="px-4 py-2 border-b">{{ $item->satuan }}</td>
                        <td class="px-4 py-2 border-b space-x-1">
                            <a href="{{ route('inventories.edit', $item->id) }}" class="bg-yellow-400 text-white px-3 py-1 rounded text-xs hover:bg-yellow-500">Edit</a>
                            <form action="" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin hapus?')" class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tableBahanBaku').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                paginate: {
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                }
            }
        });

        $('#tableBarangJadi').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                paginate: {
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    });
</script>
@endpush
