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
        padding: 5px;
    }
</style>

<body>
    <div class="row">
        <div class="col-lg-12">
            <div class="pagetitle">
                <h2>Rincian Aset Pinjaman</h2>
                <h2>{{ unitUsaha()->nm_bumdes }}</h2>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nasabah</th>
                                <th scope="col">Tanggal Pinjam</th>
                                <th scope="col">Alokasi</th>
                                <th scope="col">Realisasi</th>
                                <th scope="col">Angsuran</th>
                                <th scope="col">Sisa Pokok</th>
                                <th scope="col">Bunga</th>
                                <th scope="col">Bunga Pokok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($pinjamans as $pinjaman)
                                @php
                                    $sisa = $pinjaman->alokasi - $pinjaman->realisasi;
                                    $bunga = $pinjaman->alokasi * ($pinjaman->bunga / 100);
                                    $total_bunga = $bunga * $pinjaman->angsuran;
                                @endphp
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ $pinjaman->nasabah }}</td>
                                    <td>{{ date('Y-m-d', strtotime($pinjaman->tgl_pinjam)) }}</td>
                                    <td>{{ formatRupiah($pinjaman->alokasi) }}</td>
                                    <td>{{ formatRupiah($pinjaman->realisasi) }}</td>
                                    <td>{{ $pinjaman->angsuran }}</td>
                                    <td>{{ formatRupiah($sisa) }}</td>
                                    <td>{{ $pinjaman->bunga }}%</td>
                                    <td>{{ formatRupiah($total_bunga) }}</td>

                                </tr>
                            @endforeach
                            <tr style="font-weight: bold">
                                <td colspan="6">Total Saldo Pinjam</td>
                                <td>{{ formatRupiah($tunggakan) }}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
