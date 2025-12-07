<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    .pagetitle {
        text-align: center;
    }

    .row {
        width: 100%;
    }



    .modal {
        font-size: 25px;
        font-weight: bold;
    }

    .card {}

    table {
        border-collapse: collapse;
        width: 100%
    }

    table,
    td,
    th {
        border: 1px solid #ddd;
        text-align: left;
        padding: 3px;
        font-size: 12px;
    }
</style>

<body>
    <div class="row">
        <div class="col-lg-12">
            <div class="pagetitle">
                <h2>Rincian Buku kas</h2>
                <h2>{{ unitUsaha()->nm_bumdes }}</h2>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-hover  table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Transaksi</th>
                                <th scope="col">(Debit/Kredit)</th>
                                <th scope="col">Jenis Transaksi</th>
                                <th scope="col">Nilai</th>
                                <th scope="col">Saldo</th>
                                <th scope="col">Jenis LR</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                                $saldo = 0;
                            @endphp
                            @if ($saldo_lalu != 0)
                                @php
                                    $saldo = $saldo_lalu;
                                @endphp

                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>1-01- {{ session('selected_year', date('Y')) }}</td>
                                    <td>Saldo Awal</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ formatRupiah($saldo) }}</td>
                                    <td></td>

                                </tr>
                            @endif
                            @foreach ($transaksis as $transaksi)
                                @php
                                    if ($transaksi->jenis == 'debit') {
                                        $saldo = $transaksi->nilai + $saldo;
                                    } elseif ($transaksi->jenis == 'kredit') {
                                        $saldo = $saldo - $transaksi->nilai;
                                        # code...
                                    }

                                @endphp
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ formatTanggal($transaksi->tanggal) }}</td>
                                    <td>{{ $transaksi->transaksi }}</td>
                                    <td>
                                        <p
                                            class="{{ $transaksi->jenis == 'debit' ? 'text-success' : 'text-danger' }} fw-bold">
                                            {{ $transaksi->jenis }}</p>
                                    </td>
                                    <td>
                                        <p
                                            class="{{ $transaksi->jenis == 'debit' ? 'text-success' : 'text-danger' }} fw-bold">
                                            {{ $transaksi->jenis_dana }}
                                        </p>
                                    </td>

                                    <td>{{ formatRupiah($transaksi->nilai) }}</td>
                                    <td>{{ formatRupiah($saldo) }}</td>
                                    <td>
                                        @foreach (namaUnitUsaha() as $key => $value)
                                            @if ($transaksi->jenis_lr == $key)
                                                {{ $value }}
                                            @endif
                                        @endforeach
                                    </td>

                                </tr>
                            @endforeach
                            <tr class="bg-warning fw-bold">
                                <td colspan="6">Total Saldo</td>
                                <td>{{ formatRupiah($saldo) }}</td>
                                <td></td>

                            </tr>
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </div>
    </div>
</body>
