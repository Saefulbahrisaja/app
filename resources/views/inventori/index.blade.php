@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ open: false }">

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">Manajemen Inventori</h1>
                <p class="text-blue-100">Kelola stok bahan baku dan barang jadi</p>
            </div>
            <button @click="open = true" class="mt-4 sm:mt-0 bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition duration-200 shadow-md">
                <i class="fas fa-plus mr-2"></i>Tambah Barang Baru
            </button>
        </div>
    </div>

    <!-- Success Alert -->
    <div id="successAlert" 
        class="hidden bg-green-50 border-l-4 border-green-400 text-green-700 p-4 mb-6 rounded-lg shadow-sm transition-opacity duration-500 opacity-0"
        role="alert">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            <span class="font-medium">Perubahan berhasil disimpan!</span>
        </div>
    </div>

    <!-- Modal Tambah Barang -->
    <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300" x-transition>
        <div @click.away="open = false" class="bg-white rounded-xl shadow-2xl max-w-lg w-full mx-4 transform transition-all duration-300">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-t-xl p-6">
                <h2 class="text-xl font-bold text-white">Tambah Barang Baru</h2>
                <p class="text-blue-100 text-sm mt-1">Masukkan informasi barang yang akan ditambahkan</p>
            </div>
            <form action="{{ route('inventories.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Barang</label>
                    <input type="text" name="nama_barang" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                        placeholder="Masukkan nama barang" required>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Barang</label>
                    <select name="jenis" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Pilih jenis barang</option>
                        <option value="bahan_baku">Bahan Baku</option>
                        <option value="barang_jadi">Barang Jadi</option>
                    </select>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stok</label>
                        <input type="number" name="stok" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                            placeholder="0" min="0" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Satuan</label>
                        <input type="text" name="satuan" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                            placeholder="kg, pcs, liter, dll" required>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Satuan</label>
                    <input type="number" step="0.01" name="harga_satuan" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                        placeholder="0.00" min="0">
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" @click="open = false" 
                        class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-200 font-medium">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium shadow-md">
                        <i class="fas fa-save mr-2"></i>Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Bahan Baku -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-6 py-4">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fas fa-box mr-2"></i>Bahan Baku
            </h3>
            <p class="text-amber-100 text-sm mt-1">Daftar bahan baku yang digunakan untuk produksi</p>
        </div>
        <div class="overflow-x-auto">
            <table id="tableBahanBaku" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Bahan Baku</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Satuan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga Satuan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($bahanBaku as $item)
                    <tr data-id="{{ $item->id }}" class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" contenteditable="true" data-field="nama_barang">
                            {{ $item->nama_barang }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700" contenteditable="true" data-field="stok">
                            {{ $item->stok }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700" contenteditable="true" data-field="satuan">
                            {{ $item->satuan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700" contenteditable="true" data-field="harga_satuan">
                            Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <form action="{{ route('inventories.destroy', $item->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus barang ini?')" 
                                    class="text-red-600 hover:text-red-900 transition duration-200">
                                    <i class="fas fa-trash-alt mr-1"></i>Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabel Barang Jadi -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fas fa-cube mr-2"></i>Barang Hasil Produksi
            </h3>
            <p class="text-green-100 text-sm mt-1">Daftar barang jadi yang siap dijual</p>
        </div>
        <div class="overflow-x-auto">
            <table id="tableBarangJadi" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Produk</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Satuan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga Jual</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($barangJadi as $item)
                    <tr data-id="{{ $item->id }}" class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" contenteditable="true" data-field="nama_barang">
                            {{ $item->nama_barang }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700" contenteditable="true" data-field="stok">
                            {{ $item->stok }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700" contenteditable="true" data-field="satuan">
                            {{ $item->satuan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700" contenteditable="true" data-field="harga_satuan">
                            Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <form action="{{ route('inventories.destroy', $item->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus barang ini?')" 
                                    class="text-red-600 hover:text-red-900 transition duration-200">
                                    <i class="fas fa-trash-alt mr-1"></i>Hapus
                                </button>
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
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            },
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]]
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
            },
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]]
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function enableInlineEdit(tableSelector) {
        document.querySelectorAll(`${tableSelector} td[contenteditable]`).forEach(cell => {
            // Cek apakah ini tabel barang jadi & kolomnya stok
            if (tableSelector === '#tableBarangJadi' && cell.getAttribute('data-field') === 'stok') {
                cell.setAttribute('contenteditable', 'false');
                cell.classList.add('bg-gray-100', 'text-gray-500');
                return;
            }
            if (tableSelector === '#tableBarangJadi' && cell.getAttribute('data-field') === 'harga_satuan') {
                cell.setAttribute('contenteditable', 'false');
                cell.classList.add('bg-gray-100', 'text-gray-500');
                return;
            }

            // Event blur untuk save
            cell.addEventListener('blur', function () {
                let tr = this.closest('tr');
                let id = tr.getAttribute('data-id');
                let field = this.getAttribute('data-field');
                let value = this.innerText.trim();

                fetch(`/inventories/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ [field]: value })
                })
                .then(res => {
                    if (!res.ok) throw new Error('Gagal update');
                    return res.json();
                })
                .then(data => {
                    console.log('Update sukses', data);
                    let alertBox = document.getElementById('successAlert');
                    alertBox.classList.remove('hidden', 'opacity-0');
                    alertBox.classList.add('opacity-100');
                    setTimeout(() => {
                        alertBox.classList.add('opacity-0');
                        setTimeout(() => {
                            alertBox.classList.add('hidden');
                        }, 500);
                    }, 3000);
                })
                .catch(err => {
                    alert('Gagal menyimpan perubahan');
                    console.error(err);
                });
            });
        });
    }

    enableInlineEdit('#tableBahanBaku');
    enableInlineEdit('#tableBarangJadi');
});
</script>
@endpush
