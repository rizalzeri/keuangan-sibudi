<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rincian Aset Bayar Dimuka</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .pagetitle {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="pagetitle">
        <h3>Rincian Aset Bayar Dimuka</h3>
        <h3>{{ unitUsaha()->nm_bumdes }}</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Posisi Kas</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
                $totalBunga = 0;
            @endphp
            @foreach ($rekonsiliasis as $rekonsiliasi)
                @php $totalBunga += $rekonsiliasi->jumlah; @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $rekonsiliasi->posisi }}</td>
                    <td>{{ formatRupiah($rekonsiliasi->jumlah) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" style="text-align:right">
                    Total
                </td>
                <td>{{ formatRupiah($totalBunga) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
