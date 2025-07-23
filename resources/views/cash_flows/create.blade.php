@extends('layouts.app')

@section('content')
<form action="{{ route('cash-flows.store') }}" method="POST">
    @csrf
    <label>Jenis Transaksi</label>
    <select name="type" required>
        <option value="masuk">Kas Masuk</option>
        <option value="keluar">Kas Keluar</option>
    </select>

    <label>Kategori</label>
    <input type="text" name="kategori" required>

    <label>Jumlah</label>
    <input type="number" name="jumlah" step="0.01" required>

    <label>Tanggal</label>
    <input type="date" name="tanggal" required>

    <label>Keterangan</label>
    <textarea name="keterangan"></textarea>

    <button type="submit">Simpan</button>
</form>
@endsection
