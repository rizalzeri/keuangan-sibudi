<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    .pagetitle {
        text-align: center;
    }


    /* Style untuk tabel */
    .table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        margin-bottom: 1rem;
        background-color: #fff;
    }

    /* Border untuk seluruh tabel */
    .table-bordered {
        border: 1px solid #dee2e6;
    }

    /* Mengatur jarak antara teks dengan border */
    .table th,
    .table td {
        padding: 12px;
        vertical-align: middle;
        font-size: 12px;

    }

    /* Gaya untuk baris header tabel */
    .table thead th {
        background-color: #007bff;
        /* Biru sesuai dengan bg-primary */
        color: #ffffff;
        font-weight: bold;
        border-bottom: 2px solid #dee2e6;
        text-transform: uppercase;
    }

    /* Gaya untuk baris "Total" atau yang menonjol */
    .table .fw-bold {
        font-weight: 700;
    }

    /* Background untuk baris pendapatan */
    .table .bg-success {
        background-color: #28a745 !important;
    }

    /* Background untuk baris operasional */
    .table .bg-info {
        background-color: #17a2b8 !important;
    }

    /* Background untuk baris biaya */
    .table .bg-warning {
        background-color: #ffc107 !important;
    }

    /* Background untuk baris laba/rugi */
    .table .bg-secondary {
        background-color: #6c757d !important;
        color: #fff;
    }

    /* Hover effect untuk tabel */
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }

    /* Responsivitas untuk perangkat mobile */
    @media (max-width: 767.98px) {

        .table th,
        .table td {
            padding: 10px;
            font-size: 14px;
        }

        .table thead th {
            font-size: 13px;
        }

        .table td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }
</style>

<body>
    <div class="row">
        <div class="col-lg-12">

            <div class="pagetitle">
                <h2>Rincian Laba Rugi {{ session('selected_year', session('selected_year', date('Y'))) }}</h2>
                <h2>{{ unitUsaha()->nm_bumdes }}</h2>
            </div>

            <div class="card overflow-auto">
                <div class="card-body">


                    <table class="table table-bordered mt-4 ">
                        <thead>

                            <tr class="bg-primary text-light">
                                <th>#</th>
                                <th>Jenis Biaya</th>
                                <th>Januari</th>
                                <th>Februari</th>
                                <th>Maret</th>
                                <th>April</th>
                                <th>Mei</th>
                                <th>Juni</th>
                                <th>Juli</th>
                                <th>Agustus</th>
                                <th>September</th>
                                <th>Oktober</th>
                                <th>November</th>
                                <th>Desember</th>
                                <th>Akumulasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="15" class="fw-bold">Pendapatan</td>
                            </tr>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($units as $unit)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $unit->nm_unit }}</td>
                                    @foreach ($pendapatan['pu' . $unit->kode] as $value)
                                        <td>{{ formatRupiah($value) }}</td>
                                    @endforeach
                                    <td>{{ formatRupiah($tahun['pu' . $unit->kode]) }}</td>
                                </tr>
                            @endforeach

                            <tr class="fw-bold bg-success text-light">
                                <td colspan="2">Total_Pendapatan</td>
                                @foreach ($pendapatanBulan['pu'] as $total)
                                    <td>{{ formatRupiah($total) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($pendapatanTahun['pu']) }}</td>
                            </tr>

                            <tr>
                                <td colspan="15" class="fw-bold">Biaya Oprasional (Verbal)</td>
                            </tr>
                            @foreach ($units as $unit)
                                <tr>
                                    <td></td>
                                    <td>Biaya oprasional {{ $unit->nm_unit }}</td>
                                    @foreach ($pendapatan['bo' . $unit->kode] as $value)
                                        <td>{{ formatRupiah($value) }}</td>
                                    @endforeach
                                    <td>{{ formatRupiah($tahun['bo' . $unit->kode]) }}</td>
                                </tr>
                            @endforeach

                            <tr class="bg-info fw-bold">
                                <td colspan="2">Total_Biaya_Operasional</td>
                                @foreach ($pendapatanBulan['bo'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($pendapatanTahun['bo']) }}</td>
                            </tr>
                            <tr>
                                <td colspan="15" class="fw-bold">Biaya Non Oprasional Tetap</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Gaji Pengurus</td>
                                @foreach ($pendapatan['bno1'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($tahun['bno1']) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Atk</td>
                                @foreach ($pendapatan['bno2'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($tahun['bno2']) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Rapat-rapat</td>
                                @foreach ($pendapatan['bno3'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($tahun['bno3']) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Lain-lain</td>
                                @foreach ($pendapatan['bno4'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($tahun['bno4']) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Akumulasi Penyusutan</td>
                                @foreach ($pendapatan['bno5'] as $value)
                                    <td>-</td>
                                @endforeach
                                <td>{{ formatRupiah($akumulasi_penyusutan) }}</td>
                            </tr>
                            <tr class="bg-info fw-bold">
                                <td colspan="2">Total_Biaya_Non_Operasional</td>
                                @foreach ($pendapatanBulan['bno'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($pendapatanTahun['bno'] + $akumulasi_penyusutan) }}</td>
                            </tr>
                            <tr class="bg-warning fw-bold">
                                <td colspan="2">Total Biaya</td>

                                @foreach ($totalBiaya as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($akumulasiBiaya) }}</td>
                            </tr>

                            <tr class="bg-secondary fw-bold text-white">
                                <td colspan="2"> {{ $totalLabaRugi < 0 ? 'Rugi' : 'Laba' }}</td>

                                @foreach ($labaRugi as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($totalLabaRugi) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
