@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Transaksi Penjualan</h2>

    <!-- Pilih Produk -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                <select id="produkSelect" class="border rounded px-3 py-1 w-full">
                    <option value="">Pilih Produk</option>
                    @foreach ($productions as $item)
                        <option 
                            value="{{ $item->produk }}"
                            data-harga="{{ $item->harga_jual }}"
                            data-stok="{{ $item->stok }}"
                            data-hpp="{{ $item->hpp }}">
                            {{ $item->produk }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                <input type="number" id="jumlahInput" class="border rounded px-3 py-1 w-full" min="1">
            </div>
            <div class="flex items-end">
                <button id="tambahBtn" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Tambah ke Transaksi
                </button>
            </div>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-3">Detail Transaksi</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border text-sm" id="transaksiTable">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2">Produk</th>
                        <th class="border px-4 py-2">Jumlah</th>
                        <th class="border px-4 py-2">Harga Jual</th>
                        <th class="border px-4 py-2">Subtotal</th>
                        <th class="border px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    <!-- Diisi lewat JS -->
                </tbody>
            </table>
        </div>
        <div class="text-right mt-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-end gap-4">
                 <label class="block text-sm font-medium text-gray-700">Total belanja (Rp)</label>
            </div>
            <p id="totalRupiah" class="text-lg font-semibold text-blue-600">Rp 0</p>
            
        </div>
        
        <div class="text-right mt-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-end gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pembayaran (Rp)</label>
                    <input type="number" placeholder="0" id="pembayaranInput" class="border rounded px-3 py-1 w-full" min="0">
                </div>                
            </div>
        </div>
        <div class="text-right mt-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-end gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kembalian (Rp)</label>
                    <p id="kembalianText" class="text-lg font-semibold text-green-600">Rp 0</p>
                </div>
            </div>
        </div>
        <div class="text-right mt-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-end gap-4">
                <div class="flex items-end">
                    <button class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded" onclick="submitTransaksi()">
                        Simpan Transaksi
                    </button>
                </div>
            </div>
        </div>

    </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const transaksi = [];

function formatRupiah(num) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
}

function refreshTable() {
    const tbody = document.getElementById('tbody');
    tbody.innerHTML = '';

    let total = 0;
    let labaTotal = 0;

    transaksi.forEach((item, index) => {
        const subtotal = item.harga * item.jumlah;
        const laba = (item.harga - item.hpp) * item.jumlah;
        total += subtotal;
        labaTotal += laba;

        tbody.innerHTML += `
            <tr>
                <td class="border px-4 py-2">${item.produk}</td>
                <td class="border px-4 py-2">${item.jumlah}</td>
                <td class="border px-4 py-2">${formatRupiah(item.harga)}</td>
                <td class="border px-4 py-2">${formatRupiah(subtotal)}</td>
                <td class="border px-4 py-2 text-red-500 cursor-pointer" onclick="hapusItem(${index})">Hapus</td>
            </tr>
        `;
    });

    document.getElementById('totalRupiah').innerText = formatRupiah(total);
    document.getElementById('labaRupiah').innerText = formatRupiah(labaTotal);
}

function hapusItem(index) {
    transaksi.splice(index, 1);
    refreshTable();
}

document.getElementById('tambahBtn').addEventListener('click', () => {
    const select = document.getElementById('produkSelect');
    const jumlahBaru = parseInt(document.getElementById('jumlahInput').value);
    const option = select.options[select.selectedIndex];

    if (!option.value || !jumlahBaru || jumlahBaru < 1) return;

    const produk = option.value;
    const harga = parseFloat(option.dataset.harga);
    const hpp = parseFloat(option.dataset.hpp);
    const stok = parseInt(option.dataset.stok);

    // Cek apakah produk sudah ada
    const existing = transaksi.find(item => item.produk === produk);

    if (existing) {
        // Tambahkan jumlahnya jika tidak melebihi stok
        const totalJumlah = existing.jumlah + jumlahBaru;
        if (totalJumlah > stok) {
            alert(`Jumlah melebihi stok (${stok}) untuk produk: ${produk}`);
            return;
        }
        existing.jumlah = totalJumlah;
    } else {
        // Tambahkan produk baru
        transaksi.push({
            produk: produk,
            jumlah: jumlahBaru,
            harga: harga,
            hpp: hpp,
            stok: stok
        });
    }

    refreshTable();
    select.selectedIndex = 0;
    document.getElementById('jumlahInput').value = '';
});

function submitTransaksi() {
    const total = transaksi.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);
    const pembayaran = parseFloat(document.getElementById('pembayaranInput').value);

    if (transaksi.length === 0) {
        alert('Transaksi kosong!');
        return;
    }

    if (!pembayaran || pembayaran < total) {
        alert('Pembayaran tidak cukup!');
        return;
    }

    // Validasi stok
    const stokKurang = transaksi.find(item => item.jumlah > item.stok);
    if (stokKurang) {
        alert(`Stok tidak cukup untuk produk: ${stokKurang.produk}`);
        return;
    }

    const kembalian = pembayaran - total;
    document.getElementById('kembalianText').innerText = formatRupiah(kembalian);

    // Simpan ke database via AJAX
    fetch('{{ route("penjualan.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            transaksi: transaksi,
            total: total,
            pembayaran: pembayaran,
            kembalian: kembalian
        })
    })
    .then(res => {
        if (!res.ok) throw new Error("Terjadi kesalahan saat menyimpan");
        return res.json();
    })
    .then(res => {
        // Tampilkan struk
        let struk = '===== STRUK PENJUALAN =====\n';
        transaksi.forEach(item => {
            struk += `${item.produk} x ${item.jumlah} = ${formatRupiah(item.harga * item.jumlah)}\n`;
        });
        struk += '-----------------------------\n';
        struk += `Total     : ${formatRupiah(total)}\n`;
        struk += `Bayar     : ${formatRupiah(pembayaran)}\n`;
        struk += `Kembalian : ${formatRupiah(kembalian)}\n`;
        struk += '=============================\n';

        const win = window.open();
        win.document.write('<pre>' + struk + '</pre>');
        win.print();

        // Reset tampilan
        transaksi.length = 0;
        refreshTable();
        document.getElementById('pembayaranInput').value = '';
        document.getElementById('kembalianText').innerText = 'Rp 0';
    });
}

</script>
@endpush
