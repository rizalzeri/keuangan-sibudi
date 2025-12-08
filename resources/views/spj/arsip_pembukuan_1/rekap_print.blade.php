<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>{{ $title ?? 'Rekapitulasi' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #222;
            margin: 0;
            padding: 0;
            background: #fff;
            height: 100%; /* Tambahkan ini */
        }

        /* container: border tebal di keempat sisi */
        .page {
            box-sizing: border-box;
            position: relative;
            min-height: calc(250mm - 20mm); /* tinggi A4 dikurangi margin */
            padding: 10mm;
            background: #fff;
            display: flex;
            flex-direction: column;

            /* border di semua sisi */
            border: 2px solid #222;
        }



        /* Tambahkan container untuk konten */
        .page-content {
            flex: 1;
        }

        .title {
            text-align: center;
            margin-top: 8px;
            margin-bottom: 6px;
        }
        .title h1 {
            font-size: 18px;
            margin: 0;
            letter-spacing: 0.3px;
        }
        .title .year {
            font-size: 14px;
            margin-top: 6px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
            table-layout: auto;
            word-wrap: break-word;
        }
        table, table thead, table tbody, table th, table td {
            border: 1px solid #444;
        }
        table thead th {
            padding: 8px 6px;
            text-align: left;
            font-weight: 700;
            font-size: 12px;
            background: #f5f5f5;
            border-bottom: 2px solid #222;
        }
        table tbody td {
            padding: 6px 4px;
            vertical-align: top;
            font-size: 11px;
        }

        /* kolom center */
        .text-center { text-align: center; }

        /* pastikan No dan Tautan tidak terlalu kecil */
        th:nth-child(1), td:nth-child(1) { width: 40px; }
        th:nth-child(6), td:nth-child(6) { width: 80px; }

        .small { font-size: 11px; color: #555; }

        /* Page break control */
        tr { page-break-inside: avoid; }
        table { page-break-inside: auto; }

        @media print {
            body {
                background: #fff;
                height: 100%;
            }
            .page {
                border: 3px solid #222;
                padding: 18px 24px;
                border-radius: 0;
                min-height: calc(100vh - 40mm);
                height: calc(100vh - 40mm);
                position: relative;
            }
            table, table th, table td { border: 1px solid #222; }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="page-content">
            <div class="title">
                <h1>{{ $title ?? 'Rekapitulasi Pembukuan SPJ Pembukuan 1' }}</h1>
                <div class="year">Tahun Anggaran: {{ $selectedYear }}</div>
                @if(isset($selectedType) && $selectedType && $selectedType !== 'Semua')
                    <div class="small">Filter: {{ $selectedType }}</div>
                @endif
            </div>

            <table>
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Transaksi</th>
                        <th>Nomor Dokumen</th>
                        <th>Jenis SPJ</th>
                        <th>Dokumen Bukti Dukung</th>
                        <th class="text-center">Tautan</th>
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
                            <td class="text-center">{{ $r['tautan'] ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center small">Tidak ada data untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
