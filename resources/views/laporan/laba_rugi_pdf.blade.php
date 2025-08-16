<!DOCTYPE html>
<html>
<head>
    <title>Laporan Laba Rugi</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; }
    </style>
</head>
<body>
    <h2>Laporan Laba Rugi</h2>
    <p>Periode: {{ $start_date }} s/d {{ $end_date }}</p>
    <table>
        <tr>
            <th>Pendapatan</th>
            <td>Rp {{ number_format($pendapatan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>HPP</th>
            <td>Rp {{ number_format($hpp, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Laba Kotor</th>
            <td>Rp {{ number_format($laba_kotor, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Biaya Operasional</th>
            <td>Rp {{ number_format($biaya_operasional, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Laba Bersih</th>
            <td>Rp {{ number_format($laba_bersih, 0, ',', '.') }}</td>
        </tr>
    </table>
</body>
</html>
