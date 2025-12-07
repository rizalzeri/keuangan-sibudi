<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Arsip SPJ - {{ $year }}</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        body { font-family: Arial, Helvetica, sans-serif; padding:24px; }
        h3 { text-align:center; margin-bottom:10px; }
        .meta { text-align:center; margin-bottom:18px; color:#333; }
        table { width:100%; border-collapse: collapse; margin-top:10px; }
        table, th, td { border:1px solid #444; }
        th, td { padding:8px 10px; text-align:left; font-size:14px; }
        th { background:#f2f2f2; }
        .text-center { text-align:center; }
        .print-btn { display:block; margin: 12px auto; padding:8px 12px; background:#1976d2; color:#fff; border:none; border-radius:6px; cursor:pointer;}
    </style>
</head>
<body>
    <h3>Rekap Arsip SPJ Pembukuan</h3>
    <div class="meta">Tahun Anggaran: <strong>{{ $year }}</strong> — Filter: <strong>{{ $filter }}</strong></div>

    <button class="print-btn" onclick="window.print()">Cetak / Simpan PDF</button>

    <table>
        <thead>
            <tr>
                <th style="width:60px">No</th>
                <th>Transaksi</th>
                <th>Nomor Dokumen</th>
                <th>Jenis SPJ</th>
                <th>Bukti Dukung</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $i => $r)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $r['transaksi'] }}</td>
                    <td>{{ $r['nomor'] }}</td>
                    <td>{{ $r['jenis'] }}</td>
                    <td>{{ $r['bukti'] }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
