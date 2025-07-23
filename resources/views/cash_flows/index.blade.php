@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card mb-4">
        <div class="card-body">
            
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-auto">
                    <label for="start_date" class="form-label mb-0">Filter Tanggal:</label>
                </div>
                <div class="col-auto">
                    <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $tanggalMulai->toDateString() }}">
                </div>
                <div class="col-auto">
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $tanggalAkhir->toDateString() }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                </div>
            </form>
        </div>
    </div>
<button type="button" class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#cashFlowModal">
            Tambah Kas
        </button>
    <div class="mb-4">
        
        <h3>Laporan Arus Kas</h3>
        <p>Periode: <strong>{{ $tanggalMulai->format('d M Y') }} - {{ $tanggalAkhir->format('d M Y') }}</strong></p>
        <div class="row">
            <div class="col-md-4">
                <div class="alert alert-success mb-2">
                    Kas Masuk: <strong>Rp{{ number_format($totalMasuk, 0, ',', '.') }}</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-danger mb-2">
                    Kas Keluar: <strong>Rp{{ number_format($totalKeluar, 0, ',', '.') }}</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-info mb-2">
                    Saldo: <strong>Rp{{ number_format($saldo, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>
        
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $row)
                    <tr>
                        <td>{{ $row->tanggal->format('d M Y') }}</td>
                        <td>{{ ucfirst($row->type) }}</td>
                        <td>{{ $row->kategori }}</td>
                        <td>Rp{{ number_format($row->jumlah, 0, ',', '.') }}</td>
                        <td>{{ $row->keterangan }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data arus kas pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="cashFlowModal" tabindex="-1" aria-labelledby="cashFlowModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cashFlowModalLabel">Tambah Cash Flow</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
          </div>
          <div class="mb-3">
            <label for="type" class="form-label">Jenis</label>
            <select class="form-select" id="type" name="type" required>
              <option value="masuk">Masuk</option>
              <option value="keluar">Keluar</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <input type="text" class="form-control" id="kategori" name="kategori" required>
          </div>
          <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" class="form-control" id="jumlah" name="jumlah" required>
          </div>
          <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
