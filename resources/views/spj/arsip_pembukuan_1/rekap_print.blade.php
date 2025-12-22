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
            height: 100%;
        }

        .page {
            box-sizing: border-box;
            position: relative;
            min-height: calc(250mm - 20mm);
            padding: 10mm;
            background: #fff;
            display: flex;
            flex-direction: column;
            border: 2px solid #222;
        }

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
            /* table-layout: auto; (default, jadi bisa dihapus) */
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
            vertical-align: top;
        }
        table tbody td {
            padding: 6px 4px;
            vertical-align: top;
            font-size: 11px;
        }

        /* kolom center */
        .text-center { text-align: center; }

        /* ---- SET MINIMUM WIDTHS INSTEAD OF FIXED WIDTHS ---- */
        /* Kolom No - tetap kecil */
        th:nth-child(1), td:nth-child(1) {
            min-width: 20px;
            width: auto;
            text-align: center;
            white-space: nowrap;
        }

        /* Kolom Tanggal - cukup untuk tanggal */
        th:nth-child(2), td:nth-child(2) {
            min-width: 35px;
            width: auto;
            text-align: center;
            white-space: nowrap;
        }

        /* Kolom Transaksi - beri ruang lebih */
        th:nth-child(3), td:nth-child(3) {
            min-width: 80px;
            width: auto;
            word-wrap: break-word;
        }

        /* Kolom Nomor Dokumen */
        th:nth-child(4), td:nth-child(4) {
            min-width: 80px;
            width: auto;
            word-wrap: break-word;
        }

        /* Kolom Jenis SPJ */
        th:nth-child(5), td:nth-child(5) {
            min-width: 60px;
            width: auto;
            word-wrap: break-word;
        }

        /* Kolom Bukti Dukung */
        th:nth-child(6), td:nth-child(6) {
            min-width: 80px;
            width: auto;
            word-wrap: break-word;
        }

        /* Kolom Tautan */
        th:nth-child(7), td:nth-child(7) {
            min-width: 90px;
            width: auto;
            word-wrap: break-word;
            word-break: break-all;
        }

        /* Atur agar tautan tidak terlalu panjang */
        th:nth-child(7),
        td:nth-child(7) {
            word-break: break-all;
            overflow-wrap: anywhere;
        }

        /* Link harus boleh patah */
        td:nth-child(7) a {
            display: block;
            white-space: normal;
            word-break: break-all;
            overflow-wrap: anywhere;
        }
        .small { font-size: 11px; color: #555; }

        /* Page break control */
        tr { page-break-inside: avoid; }
        table { page-break-inside: auto; }

        @media print {
            body { background: #fff; height: 100%; }
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
                <colgroup>
                    <col style="min-width:20px">
                    <col style="min-width:35px">
                    <col style="min-width:80px">
                    <col style="min-width:80px">
                    <col style="min-width:60px">
                    <col style="min-width:80px">
                    <col style="width:120px"> <!-- TAUTAN -->
                </colgroup>
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Tanggal<br>Transaksi</th>
                        <th>Transaksi</th>
                        <th>Nomor<br>Dokumen</th>
                        <th>Jenis SPJ</th>
                        <th>Dokumen Bukti<br>Dukung</th>
                        <th class="text-center">Tautan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $i => $r)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td class="text-center">{{ $r['tanggal'] ?? '' }}</td>
                            <td>{{ $r['transaksi'] }}</td>
                            <td>{{ $r['nomor'] }}</td>
                            <td>{{ $r['jenis'] }}</td>
                            <td>{{ $r['bukti'] }}</td>
                            <td class="text-center">
                                @if(!empty($r['tautan']))
                                    <a href="{{ $r['tautan'] }}" target="_blank" rel="noopener noreferrer" title="{{ $r['tautan'] }}">
                                        {{ Str::limit($r['tautan'], 40) }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center small">Tidak ada data untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>