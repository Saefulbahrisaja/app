@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-6">Transaksi Penjualan</h2>

    <!-- Pilih Produk -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block mb-1">Produk</label>
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
                <label class="block mb-1">Jumlah</label>
                <input type="number" id="jumlahInput" class="border rounded px-3 py-1 w-full" min="1">
            </div>
            <div class="flex items-end">
                <button id="tambahBtn" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Tambah
                </button>
            </div>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h4 class="text-lg font-semibold mb-3">Detail Transaksi</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border text-sm" id="transaksiTable">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2">Produk</th>
                        <th class="border px-4 py-2">Jumlah</th>
                        <th class="border px-4 py-2">Harga Jual</th>
                        <th class="border px-4 py-2">Subtotal</th>
                        <th class="border px-4 py-2">Laba</th>
                        <th class="border px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbody"></tbody>
            </table>
        </div>
        <div class="text-right mt-4">
            <p class="text-lg font-semibold text-blue-600">Total: <span id="totalRupiah">Rp 0</span></p>
            <p class="text-lg font-semibold text-green-600">Laba: <span id="labaRupiah">Rp 0</span></p>
        </div>

        <!-- Input pembayaran -->
        <div class="mt-4 flex gap-4 justify-end">
            <div>
                <label>Pembayaran (Rp)</label>
                <input type="number" id="pembayaranInput" class="border rounded px-3 py-1 w-full" min="0">
            </div>
            <div>
                <label>Kembalian</label>
                <p id="kembalianText" class="text-lg font-semibold text-green-600">Rp 0</p>
            </div>
        </div>

        <!-- Tombol Simpan -->
        <div class="mt-4 text-right">
            <button class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded" onclick="submitTransaksi()">
                Simpan Transaksi
            </button>
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
                <td class="border px-4 py-2">${formatRupiah(laba)}</td>
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

    const existing = transaksi.find(item => item.produk === produk);
    if (existing) {
        const totalJumlah = existing.jumlah + jumlahBaru;
        if (totalJumlah > stok) {
            alert(`Stok tidak cukup (${stok}) untuk ${produk}`);
            return;
        }
        existing.jumlah = totalJumlah;
    } else {
        if (jumlahBaru > stok) {
            alert(`Stok tidak cukup (${stok}) untuk ${produk}`);
            return;
        }
        transaksi.push({ produk, jumlah: jumlahBaru, harga, hpp, stok });
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

    const kembalian = pembayaran - total;
    document.getElementById('kembalianText').innerText = formatRupiah(kembalian);

    fetch('{{ route("penjualan.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            transaksi,
            total,
            pembayaran,
            kembalian
        })
    })
    .then(res => res.json())
    .then(res => {
        alert('Transaksi berhasil disimpan');
        transaksi.length = 0;
        refreshTable();
        document.getElementById('pembayaranInput').value = '';
        document.getElementById('kembalianText').innerText = 'Rp 0';
    });
}
</script>
@endpush
