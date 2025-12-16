{{-- resources/views/spj/arsip_notulen_rapat/cetak_pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Hadir Rapat</title>
    <style>
        @page {
            margin: 15mm;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 14px;
            margin: 0 0 8px 0;
            text-decoration: underline;
        }
        .info-rapat {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .info-rapat td {
            padding: 2px 7px;
            vertical-align: top;
            font-size: 12px;

        }
        .info-rapat .label {
            width: 120px;
            font-weight: bold;
        }
        .tabel-peserta {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .tabel-peserta th,
        .tabel-peserta td {
            border: 1px solid #000;
            padding: 0;
            text-align: center;
            height: 30px;
        }
        .tabel-peserta th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        /* Perbaikan lebar kolom */
        .tabel-peserta th:nth-child(1) { width: 40px; } /* No */
        .tabel-peserta th:nth-child(2) { width: 120px; } /* Nama */
        .tabel-peserta th:nth-child(3) { width: 100px; } /* Unsur/Jabatan */
        .tabel-peserta th:nth-child(4) { width: 120px; } /* Alamat */
        .tabel-peserta th:nth-child(5) { width: 100px; } /* Tanda Tangan */

        .tanda-tangan {
            height: 50px;
            position: relative;
        }
        .nomor-tanda-tangan {
            position: absolute;
            bottom: 3px;
            font-size: 12px;
            color: #333;
        }
        /* Untuk posisi kiri */
        .nomor-kiri {
            left: 8%;
            transform: translateX(-50%);
        }

        /* Untuk posisi kanan (sebelah kanan tengah) */
        .nomor-kanan {
            left: 50%;
            transform: translateX(-50%);
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        .info-rapat td.label {
            padding-right: 5px;
            text-align: left;
            white-space: nowrap;
        }

        .info-rapat td.colon {
            width: 10px;
            text-align: center;
        }

    </style>
</head>
<body>
    <div class="header">
        <h1>DAFTAR HADIR RAPAT</h1>
    </div>

    <table class="info-rapat">
        <tr>
            <td class="label">Hari, Tanggal</td>
            <td class="colon">:</td>
            <td>{{ $hari }}, {{ $tanggal }}</td>
        </tr>
        <tr>
            <td class="label">Waktu</td>
            <td class="colon">:</td>
            <td>{{ $waktu }}</td>
        </tr>
        <tr>
            <td class="label">Tempat</td>
            <td class="colon">:</td>
            <td>{{ $tempat }}</td>
        </tr>
        <tr>
            <td class="label">Acara</td>
            <td class="colon">:</td>
            <td>{{ $acara }}</td>
        </tr>
    </table>


    <table class="tabel-peserta">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Unsur/Jabatan</th>
                <th>Alamat</th>
                <th>Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @for($i = 1; $i <= 58; $i++)
                <tr>
                    <td>{{ $i }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="tanda-tangan">
                        @php
                            // Logika untuk posisi nomor tanda tangan
                            // Jika nomor ganjil => kiri, genap => kanan
                            if($i % 2 == 1) {
                                // Ganjil: posisi kiri
                                $positionClass = 'nomor-kiri';
                            } else {
                                // Genap: posisi kanan
                                $positionClass = 'nomor-kanan';
                            }
                        @endphp
                        <div class="nomor-tanda-tangan {{ $positionClass }}">
                            {{ $i }}
                        </div>
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>

    {{-- <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
    </div> --}}
</body>
</html>