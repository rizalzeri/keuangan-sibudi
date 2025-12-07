<style>
    body {
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
    }

    /* Gaya umum untuk tabel */
    .table {
        width: 100%;
        background-color: #fff;
        border-collapse: collapse;
    }

    h5 {
        padding: 0px;
    }

    /* Gaya untuk border tabel */
    .table th,
    .table td {
        padding: 5px;
        font-size: 16px
    }


    /* Kolom yang mencakup beberapa kolom */
    .table thead th[colspan] {
        text-align: center;
    }

    /* Gaya untuk baris total */
    .table .fw-bold {
        font-weight: 700;

    }

    .bg-yellow {
        background-color: #ffd900;
    }

    /* Gaya untuk perbedaan untung/rugi */
    .table td strong {
        font-weight: bold;
        color: #dc3545;
        /* Warna merah jika rugi */
    }

    .ms {
        margin-left: 20px
    }

    .text-red {
        color: #dc3545;
    }
</style>


<div class="row">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">

                <table class="table">
                    <thead>
                        <tr>
                            <td class="fw-bold">1. LAPORAN NERACA</td>
                        </tr>
                        <tr class="text-center">
                            <th colspan="2">
                                Aktiva
                            </th>
                            <th colspan="2">
                                Passiva
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="ms"> Kas atau setara Kas</span></td>
                            <td class="text-red">{{ formatRupiah($kas) }}</td>
                            <td><span class="ms">Hutang</span></td>
                            <td class="text-red">{{ formatRupiah($hutang) }}</td>
                        </tr>
                        <tr>
                            <td><span class="ms">Bank</span></span></td>
                            <td class="text-red">{{ formatRupiah($bank) }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><span class="ms">Piutang</span></span></td>
                            <td class="text-red">{{ formatRupiah($piutang) }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        @can('referral')
                            <tr>
                                <td><span class="ms">Saldo Pinjam</span></td>
                                <td class="text-red">{{ formatRupiah($saldo_pinjam) }}</td>
                                <td><span class="ms"> Simpanan Pokok</span></td>
                                <td class="text-red">{{ formatRupiah($modal_desa) }}</td>
                            </tr>
                            <tr>
                                <td><span class="ms">Persediaan dagang</span></td>
                                <td class="text-red">{{ formatRupiah($persediaan_dagang) }}</td>
                                <td><span class="ms">Simpanan Wajib</span></td>
                                <td class="text-red">{{ formatRupiah($modal_masyarakat) }}</td>
                            </tr>
                            <tr>
                                <td> <span class="ms">Biaya Dibayar di muka</span></td>
                                <td class="text-red">{{ formatRupiah($bayar_dimuka) }}</td>
                                <td><span class="ms">Simpanan Sukarela</span></td>
                                <td class="text-red">{{ formatRupiah($modal_bersama) }}</td>
                            </tr>
                            <tr>
                                <td><span class="ms">Iventaris</span></td>
                                <td class="text-red">{{ formatRupiah($investasi) }}</td>
                                <td><span class="ms">{{ $ditahan < 0 ? 'Rugi' : 'Laba' }} ditahan </span></td>
                                <td class="text-red">{{ formatRupiah($ditahan) }}</td>
                            <tr>
                                <td><span class="ms">Bangunan</span></td>
                                <td class="text-red">{{ formatRupiah($bangunan) }}</td>
                                <td><span class="ms">{{ $laba_rugi_berjalan < 0 ? 'Rugi' : 'Laba' }} Berjalan
                                    </span><strong>
                                    </strong></td>
                                <td class="text-red">{{ formatRupiah($laba_rugi_berjalan) }}</td>
                            </tr>
                        @else
                            <tr>
                                <td><span class="ms">Saldo Pinjam</span></td>
                                <td class="text-red">{{ formatRupiah($saldo_pinjam) }}</td>
                                <td><span class="ms">Pernyertaan Modal Desa</span></td>
                                <td class="text-red">{{ formatRupiah($modal_desa) }}</td>
                            </tr>
                            <tr>
                                <td><span class="ms">Persediaan dagang</span></td>
                                <td class="text-red">{{ formatRupiah($persediaan_dagang) }}</td>
                                <td><span class="ms">Pernyertaan Modal Masyarakat</span></td>
                                <td class="text-red">{{ formatRupiah($modal_masyarakat) }}</td>
                            </tr>
                            <tr>
                                <td><span class="ms">Biaya Dibayar di muka</span></td>
                                <td class="text-red">{{ formatRupiah($bayar_dimuka) }}</td>
                                <td><span class="ms">{{ $ditahan < 0 ? 'Rugi' : 'Laba' }} ditahan </span></td>
                                <td class="text-red">{{ formatRupiah($ditahan) }}</td>
                            </tr>
                            <tr>
                                <td><span class="ms">Iventaris</td>
                                <td class="text-red"> {{ formatRupiah($investasi) }}</td>
                                <td><span class="ms">{{ $laba_rugi_berjalan < 0 ? 'Rugi' : 'Laba' }} Berjalan
                                    </span><strong>
                                    </strong></td>
                                <td class="text-red">{{ formatRupiah($laba_rugi_berjalan) }}</td>

                            <tr>
                                <td><span class="ms">Bangunan</span></td>
                                <td class="text-red">{{ formatRupiah($bangunan) }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endcan
                        <tr>
                            <td><span class="ms">Aktiva Lain</span></td>
                            <td class="text-red">{{ formatRupiah($aktiva_lain) }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="fw-bold">
                            <td>Total Aktiva</td>
                            <td class="text-red">{{ formatRupiah($total_aktiva) }}</td>
                            <td>Total Pasiva</td>
                            <td class="text-red">{{ formatRupiah($passiva) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
