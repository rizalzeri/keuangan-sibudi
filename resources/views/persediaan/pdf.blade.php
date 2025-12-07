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
        font-size: 12px
    }
</style>

<body>
    <div class="row">
        <div class="col-lg-12">
            <div class="pagetitle">
                <h2>Rincian Aset Persediaan</h2>
                <h2>{{ unitUsaha()->nm_bumdes }}</h2>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table datatable  table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Item</th>
                                <th scope="col">Satuan</th>
                                <th scope="col">HPP</th>
                                <th scope="col">Nilai_Jual</th>
                                <th scope="col">Jumlah_Awal</th>
                                <th scope="col">Nilai_Awal</th>
                                <th scope="col">Masuk</th>
                                <th scope="col">Keluar</th>
                                <th scope="col">Jumlah_Akhir</th>
                                <th scope="col">Nilai_Akhir</th>
                                <th scope="col">Laba</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($barangs as $barang)
                                @php
                                    $nilai_awal = $barang->jml_awl * $barang->hpp;
                                    $jumlah_akhir = $barang->jml_awl - ($barang->masuk - $barang->keluar);
                                    $jumlah = $barang->jml_awl - $barang->masuk;
                                    $nilai_akhir = $jumlah_akhir * $barang->hpp;
                                    $laba = ($barang->jml_awl - $jumlah) * ($barang->nilai_jual - $barang->hpp);
                                @endphp
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ $barang->item }}</td>
                                    <td>{{ $barang->satuan }}</td>
                                    <td>{{ formatRupiah($barang->hpp) }}</td>
                                    <td>{{ formatRupiah($barang->nilai_jual) }}</td>
                                    <td>
                                        <p class="text-center">{{ formatNomor($barang->jml_awl) }}</p>
                                    </td>
                                    <td>{{ formatRupiah($nilai_awal) }}</td>
                                    <td>
                                        {{ $barang->masuk == null ? 0 : $barang->masuk }}
                                    </td>
                                    <td>
                                        {{ $barang->keluar == null ? 0 : $barang->keluar }}
                                    </td>
                                    <td>
                                        <p class="text-center">{{ formatNomor($jumlah_akhir) }}</p>
                                    </td>
                                    <td>{{ formatRupiah($nilai_akhir) }}</td>
                                    <td>{{ formatRupiah($laba) }}</td>


                                </tr>
                            @endforeach
                            <tr style="font-weight: bold">
                                <td colspan="6"></td>
                                <td>{{ formatRupiah($total_nilai_awal) }}</td>
                                <td colspan="3"></td>
                                <td>{{ formatRupiah($total_nilai_akhir) }}</td>
                                <td></td>
                            </tr>
                            <tr style="font-weight: bold">
                                <td colspan="11">Laba</td>
                                <td>{{ formatRupiah($total_laba) }}</td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
