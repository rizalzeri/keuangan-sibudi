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
    }
</style>

<body>
    <div class="row">
        <div class="col-lg-12">
            <div class="pagetitle">
                <h2>Rincian Aset Piutang</h2>
                <h2>{{ unitUsaha()->nm_bumdes }}</h2>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Kreditur</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Nilai</th>
                                <th scope="col">Pembayaran</th>
                                <th scope="col">Sisa</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($piutangs as $piutang)
                                @php
                                    $sisa_total = $piutang->nilai - $piutang->pembayaran;
                                @endphp
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ $piutang->kreditur }}</td>
                                    <td>{{ $piutang->keterangan }}</td>
                                    <td>{{ formatRupiah($piutang->nilai) }}</td>
                                    <td>{{ formatRupiah($piutang->pembayaran) }}</td>
                                    <td>{{ formatRupiah($sisa_total) }}</td>

                                </tr>
                            @endforeach
                            <tr style="font-weight: bold">
                                <td colspan="5">Sisa Piutang</td>
                                <td>{{ formatRupiah($sisa) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
