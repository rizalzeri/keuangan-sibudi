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
        font-size: 12px
    }
</style>

<body>
    <div class="row">
        <div class="col-lg-12">

            <div class="pagetitle">
                <h3>Rincian Aset Bayar Dimuka</h3>
                <h3>{{ unitUsaha()->nm_bumdes }}</h3>
            </div>

            <div class="card overflow-auto">
                <div class="card-body">


                    <table class="table table-striped table-hover datatable">
                        <thead>
                            <tr style="background-color:yellow">
                                <th scope="col">#</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Nilai</th>
                                <th scope="col">Waktu Ekonomis</th>
                                <th scope="col">Masa Pakai</th>
                                <th scope="col">Perhitungan Biaya Pertahun</th>
                                <th scope="col">Nilai_Saat_Ini</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($asets as $aset)
                                @php
                                    if ($aset->wkt_ekonomis != 0) {
                                        $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
                                    }

                                    $masa_pakai =
                                        masaPakai($aset->created_at, $aset->wkt_ekonomis)['masa_pakai'] * $penyusutan;
                                    // Hitung nilai aset saat ini berdasarkan masa pakai dan penyusutan
                                    $saat_ini = $aset->nilai - $masa_pakai;
                                    if ($masa_pakai == 0 || $saat_ini == 0) {
                                        $penyusutan = 0;
                                    }

                                @endphp
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ formatTanggal($aset->created_at) }}</td>
                                    <td>{{ $aset->jenis }}</td>
                                    <td>{{ formatRupiah($aset->nilai) }}</td> <!-- Format nilai dengan formatRupiah -->
                                    <td>{{ $aset->wkt_ekonomis }}</td>
                                    <td>
                                        {{ masaPakai($aset->created_at, $aset->wkt_ekonomis)['masa_pakai'] }}
                                    </td>
                                    <td>{{ formatRupiah($penyusutan) }}</td>
                                    <td>{{ formatRupiah($saat_ini) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="6">Total Pembelian</td>
                                <td style="font-weight: bold; background-color:yellow">
                                    {{ formatRupiah($akumulasi) }}
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="7">Total Inventaris</td>
                                <td style="font-weight: bold; background-color:yellow">
                                    {{ formatRupiah($investasi) }}
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
