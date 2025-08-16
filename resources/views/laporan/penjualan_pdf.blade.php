<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width:100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background:#eee; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h3 style="text-align:center">Laporan Penjualan</h3>
    <p>Periode: {{ $start }} s/d {{ $end }}</p>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
                <th>Modal/unit</th>
                <th>Laba Item</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $r)
            <tr>
                <td>{{ date('d M Y', strtotime($r->tanggal)) }}</td>
                <td>{{ $r->produk }}</td>
                <td class="right">{{ $r->jumlah }}</td>
                <td class="right">Rp {{ number_format($r->harga,0,',','.') }}</td>
                <td class="right">Rp {{ number_format($r->subtotal,0,',','.') }}</td>
                <td class="right">Rp {{ number_format($r->modal_per_unit ?? 0,0,',','.') }}</td>
                <td class="right">Rp {{ number_format( ($r->subtotal - (($r->modal_per_unit ?? 0) * $r->jumlah)),0,',','.') }}</td>
            </tr>
            @endforeach
            <tr>
                <th colspan="4" class="right">Total</th>
                <th class="right">Rp {{ number_format($totalPendapatan,0,',','.') }}</th>
                <th class="right">Rp {{ number_format($totalModal,0,',','.') }}</th>
                <th class="right">Rp {{ number_format($totalLaba,0,',','.') }}</th>
            </tr>
        </tbody>
    </table>
</body>
</html>
