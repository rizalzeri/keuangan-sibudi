<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    .pagetitle {
        text-align: center;
    }

    .row-modal {
        margin: 0 -5px;
        width: 100%;
    }

    .card-modal {
        border: 1px solid black;
        float: left;
        width: 200px;
        padding: 0 10px;
        margin-right: 20px;
        border-radius: 10px;
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
                <h2>Rincian Hutang</h2>
                <h2>{{ unitUsaha()->nm_bumdes }}</h2>
            </div>

            <div class="card overflow-auto">
                <div class="card-body">


                    <!-- Table with stripped rows -->
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
                            @foreach ($hutangs as $hutang)
                                @php
                                    $sisa_total = $hutang->nilai - $hutang->pembayaran;
                                @endphp
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ $hutang->kreditur }}</td>
                                    <td>{{ $hutang->keterangan }}</td>
                                    <td>{{ formatRupiah($hutang->nilai) }}</td>
                                    <td>{{ formatRupiah($hutang->pembayaran) }}</td>
                                    <td>{{ formatRupiah($sisa_total) }}</td>
                                </tr>
                            @endforeach
                            <tr style="font-weight: bold">
                                <td colspan="5">Sisa Hutang</td>
                                <td>{{ formatRupiah($sisa) }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </div>
    </div>
</body>
