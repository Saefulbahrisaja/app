@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahBarangModal">
        + Tambah Barang
    </button>

    <!-- Modal -->
    <div class="modal fade" id="tambahBarangModal" tabindex="-1" aria-labelledby="tambahBarangModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('inventories.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahBarangModalLabel">Tambah Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control mb-2" required>

                        <label class="form-label">Jenis</label>
                        <select name="jenis" class="form-select mb-2" required>
                            <option value="bahan_baku">Bahan Baku</option>
                            <option value="barang_jadi">Barang Jadi</option>
                        </select>

                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" min="0" class="form-control mb-2" required>

                        <label class="form-label">Satuan</label>
                        <input type="text" name="satuan" class="form-control mb-2" required>

                        <label class="form-label">Harga Satuan</label>
                        <input type="number" step="0.01" name="harga_satuan" class="form-control mb-2">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h3 class="mb-3">Bahan Baku</h3>
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($bahanBaku as $item)
                    <tr>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->stok }}</td>
                        <td>{{ $item->satuan }}</td>
                        <td>Rp{{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h3 class="mb-3">🛒 Barang Jadi</h3>
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($barangJadi as $item)
                    <tr>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->stok }}</td>
                        <td>{{ $item->satuan }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
