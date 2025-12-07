<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LAPORAN PENANGGUNG JAWABAN BUMDESA</title>
    <style>
        @page {
            margin: 10mm 20mm 20mm 20mm;
            /* Atas, Kanan, Bawah, Kiri */
        }

        body {
            font-family: 'Times New Roman', Cambria, Cochin, Georgia, Times, serif;
            margin: 0;
            letter-spacing: -0.1px;

        }

        .judul {
            text-align: center;

        }

        .align-justify {
            text-align: justify;
        }

        p {
            font-size: 16px;
        }

        .mb {
            margin-bottom: 70px;
        }

        .mb-100 {
            margin-bottom: 100px;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-border {
            border: 1px solid;
            padding: 5px;

        }

        .border-none,
        {
        border: 1px solid;
        border: none !important;
        border-bottom: none !important;
        }

        .page-break {
            page-break-after: always;
        }

        .isi {
            margin-left: 20px;
        }

        .p-0 {
            padding: 0px;
            margin: 0px;
        }

        .p-2 {
            padding: 10px;
            margin: 0px;
        }

        .st-title {
            width: 200px;
            padding: 10px;
            border: 1px solid;
            text-align: center;
            margin: auto;
        }

        #penasehat {
            width: 200px;
            padding: 10px;
            text-align: center;
        }

        #pengawas {
            width: 200px;
            padding: 10px;
            text-align: center;

        }

        .row {
            text-align: center;
        }

        .row div {
            margin-top: 30px;
            display: inline-block;
        }

        .garis-horisontal {
            width: 100px;
            border-top: 2px dashed;
            height: 25px;

        }

        .garis-vertikal {
            margin-top: 3px;
            left: 51%;
            border-left: 2px solid;
            height: 90px;
            position: absolute;

        }

        #direktur {
            width: 200px;
            margin: auto;
        }

        .fw-bold {
            font-weight: bold;
        }

        .mt-3 {
            margin-top: 20px;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: end;
        }
    </style>

</head>

<body>

    <p class="fw-bold judul p-0">FORMULIR ASPEK-ASPEK ANALISA KELAYAKAN USAHA</p>
    <p class="fw-bold judul p-0">TEMATIK {{ $akp->tematik }}</p>

    <p class="fw-bold p-0 mt-3">Informasi Umum Desa</p>
    <div class="isi">
        <table style="border: none">
            <tr>
                <td>1</td>
                <td class="fw-bold">Nama Desa</td>
                <td>:</td>
                <td>{{ $profil->desa }}</td>
            </tr>
            <tr>
                <td>2</td>
                <td class="fw-bold">Kecamatan</td>
                <td>:</td>
                <td>{{ $profil->kecamatan }}</td>
            </tr>
            <tr>
                <td>3</td>
                <td class="fw-bold">Kabupaten</td>
                <td>:</td>
                <td>Brebes</td>
            </tr>
            <tr>
                <td>4</td>
                <td class="fw-bold">Provinsi</td>
                <td>:</td>
                <td>Jawa Tengah</td>
            </tr>
            <tr>
                <td>5</td>
                <td class="fw-bold">Status Desa</td>
                <td>:</td>
                <td>{{ $akp->status }}</td>
            </tr>
            <tr>
                <td>6</td>
                <td class="fw-bold">Pagu Dana Desa</td>
                <td>:</td>
                <td>{{ formatRupiah($akp->dana) }}</td>
            </tr>
            <tr>
                <td>7</td>
                <td class="fw-bold">Alokasi DD untuk Ketahanan Pangan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>:</td>
                <td>{{ formatRupiah($akp->alokasi) }}</td>
            </tr>
            <tr>
                <td>8</td>
                <td class="fw-bold">Nama Bum Desa/Bum Desa Bersama</td>
                <td>:</td>
                <td>{{ $profil->nm_bumdes }}</td>
            </tr>
        </table>
    </div>



    <p class="fw-bold p-0 mt-3">1 PENGELUARAN</p>
    <div class="isi page-break">
        <p class="fw-bold p-0">A. &nbsp;&nbsp;BIAYA MODAL AWAL</p>
        <div class="isi">
            <p class="p-0"> &nbsp;&nbsp;Biaya Sewa Tanah/ Bangunan</p>
            <table class="table ">
                <tr class="text-center table-border">
                    <td class="table-border">NO</td>
                    <td class="table-border">Uraian</td>
                    <td class="table-border">Volume</td>
                    <td class="table-border">Satuan</td>
                    <td class="table-border">Harga&nbsp;Satuan</td>
                    <td class="table-border" class=" p-2">Jumlah <br>
                        Pengadaan <br>
                        pertahun</td>
                    <td class="table-border">Jumlah (Rp)</td>
                </tr>
                @php
                    $total_sewa_tanah = 0;
                    $i = 1;
                @endphp
                @foreach ($kebutuhans['Sewa Tanah/Bangunan'] as $kebutuhan)
                    @php
                        $total_sewa_tanah += $kebutuhan->harga * $kebutuhan->jumlah * $kebutuhan->volume;
                        $total_satuan = $kebutuhan->harga * $kebutuhan->jumlah * $kebutuhan->volume;
                    @endphp
                    <tr class="table-border">
                        <td class="table-border">{{ $i++ }}</td>

                        <td class="table-border">{{ $kebutuhan->uraian }}</td>
                        <td class="table-border">{{ $kebutuhan->volume }}</td>
                        <td class="table-border">{{ $kebutuhan->satuan }}</td>
                        <td class="table-border">{{ formatRupiah($kebutuhan->harga) }}</td>
                        <td class="table-border text-center">{{ $kebutuhan->jumlah }}</td>
                        <td class="table-border">{{ formatRupiah($total_satuan) }}</td>
                    </tr>
                @endforeach
                <tr class="border-none">

                    <td colspan="6" class="text-end fw-bold border-none">Total Biaya Sewa Tanah / Bangunan</td>
                    <td class="fw-bold">&nbsp;{{ formatRupiah($total_sewa_tanah) }}</td>
                </tr>
            </table>
        </div>
        <div class="isi mt-3">
            <p class="p-0"> &nbsp;&nbsp;Belanja Peralatan</p>
            <p class="p-0"> &nbsp;&nbsp;Peralatan Produksi</p>
            <table class="table ">
                <tr class="text-center table-border">
                    <td class="table-border">NO</td>
                    <td class="table-border">Uraian</td>
                    <td class="table-border">Volume</td>
                    <td class="table-border">Satuan</td>
                    <td class="table-border">Harga Satuan</td>
                    <td class="table-border" class=" p-2">Jumlah <br>
                        Pengadaan <br>
                        pertahun</td>
                    <td class="table-border">Jumlah (Rp)</td>
                </tr>
                @php
                    $total_keseluruhan = 0;
                    $i = 1;
                    $kategori = ['Pengadaan Alat', 'Sewa Alat']; // Gabungkan kategori
                    $adaData = false; // Flag untuk cek apakah ada data
                @endphp

                @foreach ($kategori as $key)
                    @if (!empty($kebutuhans[$key]) && count($kebutuhans[$key]) > 0)
                        @php $adaData = true; @endphp
                        @foreach ($kebutuhans[$key] as $kebutuhan)
                            @php
                                $total_satuan = $kebutuhan->harga * $kebutuhan->jumlah * $kebutuhan->volume;
                                $total_keseluruhan += $total_satuan;
                            @endphp
                            <tr class="table-border">
                                <td class="table-border">{{ $i++ }}</td>
                                <td class="table-border">{{ $kebutuhan->uraian }}</td>
                                <td class="table-border">{{ $kebutuhan->volume }}</td>
                                <td class="table-border">{{ $kebutuhan->satuan }}</td>
                                <td class="table-border">{{ formatRupiah($kebutuhan->harga) }}</td>
                                <td class="table-border text-center">{{ $kebutuhan->jumlah }}</td>
                                <td class="table-border">{{ formatRupiah($total_satuan) }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach

                @if (!$adaData)
                    <tr>
                        <td colspan="7" class="text-center table-border">Data tidak ada</td>
                    </tr>
                @endif

                <tr class="border-none">
                    <td colspan="6" class="text-end fw-bold border-none">Total Belanja Peralatan Produksi</td>
                    <td class="fw-bold">&nbsp;{{ formatRupiah($total_keseluruhan) }}</td>
                </tr>

            </table>
        </div>
        <div class="isi mt-3">
            <p class="p-0"> &nbsp;&nbsp;Belanja Benih/ Bibit/ Pakan</p>
            <table class="table ">
                <tr class="text-center table-border">
                    <td class="table-border">NO</td>
                    <td class="table-border">Uraian</td>
                    <td class="table-border">Volume</td>
                    <td class="table-border">Satuan</td>
                    <td class="table-border">Harga Satuan</td>
                    <td class="table-border" class=" p-2">Jumlah <br>
                        Pengadaan <br>
                        pertahun</td>
                    <td class="table-border">Jumlah (Rp)</td>
                </tr>
                @php
                    $total_bibit = 0;
                    $i = 1;
                @endphp
                @foreach ($kebutuhans['Bibit/ Benih'] as $kebutuhan)
                    @php
                        $total_bibit += $kebutuhan->harga * $kebutuhan->jumlah * $kebutuhan->volume;
                        $total_satuan = $kebutuhan->harga * $kebutuhan->jumlah * $kebutuhan->volume;
                    @endphp
                    <tr class="table-border">
                        <td class="table-border">{{ $i++ }}</td>

                        <td class="table-border">{{ $kebutuhan->uraian }}</td>
                        <td class="table-border">{{ $kebutuhan->volume }}</td>
                        <td class="table-border">{{ $kebutuhan->satuan }}</td>
                        <td class="table-border">{{ formatRupiah($kebutuhan->harga) }}</td>
                        <td class="table-border text-center">{{ $kebutuhan->jumlah }}</td>
                        <td class="table-border">{{ formatRupiah($total_satuan) }}</td>
                    </tr>
                @endforeach
                @if (count($kebutuhans['Bibit/ Benih']) <= 0)
                    <tr>
                        <td colspan="7" class="text-center table-border">Data tidak ada</td>
                    </tr>
                @endif
                <tr class="border-none">

                    <td colspan="6" class="text-end fw-bold border-none">Total Belanja Benih/ Bibit/ Pakan</td>
                    <td class="fw-bold">&nbsp;{{ formatRupiah($total_bibit) }}</td>
                </tr>
                <tr class="border-none">

                    <td colspan="6" class="text-end fw-bold border-none">TOTAL BIAYA MODAL AWAL</td>
                    <td class="fw-bold">
                        &nbsp;{{ formatRupiah($x) }}
                    </td>
                </tr>
            </table>
        </div>
    </div>


    {{-- BIAYA MODAL PRODUKSI --}}
    <div class="isi">
        <p class="fw-bold p-0">B. &nbsp;&nbsp;BIAYA MODAL PRODUKSI</p>
        <div class="isi">
            <p class="p-0"> &nbsp;&nbsp;Biaya Distribusi Potensi/Produk Unggulan Unit Usaha</p>
            <table class="table ">
                <tr class="text-center table-border">
                    <td class="table-border">NO</td>
                    <td class="table-border">Uraian</td>
                    <td class="table-border">Volume</td>
                    <td class="table-border">Satuan</td>
                    <td class="table-border">Harga Satuan</td>
                    <td class="table-border" class=" p-2">Jumlah <br>
                        Pengadaan <br>
                        pertahun</td>
                    <td class="table-border">Jumlah (Rp)</td>
                </tr>
                @php
                    $total_distribusi = 0;
                    $i = 1;
                @endphp
                @foreach ($kebutuhans['Distribusi'] as $kebutuhan)
                    @php
                        $total_distribusi += $kebutuhan->harga * $kebutuhan->jumlah * $kebutuhan->volume;
                        $total_satuan = $kebutuhan->harga * $kebutuhan->jumlah * $kebutuhan->volume;
                    @endphp
                    <tr class="table-border">
                        <td class="table-border">{{ $i++ }}</td>

                        <td class="table-border">{{ $kebutuhan->uraian }}</td>
                        <td class="table-border">{{ $kebutuhan->volume }}</td>
                        <td class="table-border">{{ $kebutuhan->satuan }}</td>
                        <td class="table-border">{{ formatRupiah($kebutuhan->harga) }}</td>
                        <td class="table-border text-center">{{ $kebutuhan->jumlah }}</td>
                        <td class="table-border">{{ formatRupiah($total_satuan) }}</td>
                    </tr>
                @endforeach
                @if (count($kebutuhans['Distribusi']) <= 0)
                    <tr>
                        <td colspan="7" class="text-center table-border">Data tidak ada</td>
                    </tr>
                @endif
                <tr class="border-none">

                    <td colspan="6" class="text-end fw-bold border-none">Total Biaya Distribusi Potensi/Produk
                        Unggulan Unit Usaha</td>
                    <td class="fw-bold">&nbsp;{{ formatRupiah($total_distribusi) }}</td>
                </tr>
            </table>
        </div>
        <div class="isi mt-3">
            <p class="p-0"> &nbsp;&nbsp; Biaya Sarana Prasarana unit usaha</p>
            <table class="table ">
                <tr class="text-center table-border">
                    <td class="table-border">NO</td>
                    <td class="table-border">Uraian</td>
                    <td class="table-border">Volume</td>
                    <td class="table-border">Satuan</td>
                    <td class="table-border">Harga Satuan</td>
                    <td class="table-border" class=" p-2">Jumlah <br>
                        Pengadaan <br>
                        pertahun</td>
                    <td class="table-border">Jumlah (Rp)</td>
                </tr>
                @php
                    $total_sarana = 0;
                    $i = 1;
                @endphp
                @foreach ($kebutuhans['Sarana Prasarana'] as $kebutuhan)
                    @php
                        $total_sarana += $kebutuhan->harga * $kebutuhan->jumlah * $kebutuhan->volume;
                        $total_satuan = $kebutuhan->harga * $kebutuhan->jumlah * $kebutuhan->volume;
                    @endphp
                    <tr class="table-border">
                        <td class="table-border">{{ $i++ }}</td>

                        <td class="table-border">{{ $kebutuhan->uraian }}</td>
                        <td class="table-border">{{ $kebutuhan->volume }}</td>
                        <td class="table-border">{{ $kebutuhan->satuan }}</td>
                        <td class="table-border">{{ formatRupiah($kebutuhan->harga) }}</td>
                        <td class="table-border text-center">{{ $kebutuhan->jumlah }}</td>
                        <td class="table-border">{{ formatRupiah($total_satuan) }}</td>
                    </tr>
                @endforeach
                @if (count($kebutuhans['Sarana Prasarana']) <= 0)
                    <tr>
                        <td colspan="7" class="text-center table-border">Data tidak ada</td>
                    </tr>
                @endif
                <tr class="border-none">

                    <td colspan="6" class="text-end fw-bold border-none">Total Biaya Sarana Prasarana unit usaha
                    </td>
                    <td class="fw-bold">&nbsp;{{ formatRupiah($total_sarana) }}</td>
                </tr>
            </table>
        </div>
        <div class="isi mt-3">
            <p class="p-0"> &nbsp;&nbsp; Biaya Pemeliharaan Potensi/ Produk Unggulan Unit Usaha</p>
            <table class="table ">
                <tr class="text-center table-border">
                    <td class="table-border">NO</td>
                    <td class="table-border">Uraian</td>
                    <td class="table-border">Volume</td>
                    <td class="table-border">Satuan</td>
                    <td class="table-border">Harga Satuan</td>
                    <td class="table-border" class=" p-2">Jumlah <br>
                        Pengadaan <br>
                        pertahun</td>
                    <td class="table-border">Jumlah (Rp)</td>
                </tr>
                @php
                    $total_pemeliharaan = 0;
                    $i = 1;
                @endphp
                @foreach ($kebutuhans['Bahan Pemeliharaan'] as $kebutuhan)
                    @php
                        $total_pemeliharaan += $kebutuhan->harga * $kebutuhan->jumlah * $kebutuhan->volume;
                        $total_satuan = $kebutuhan->harga * $kebutuhan->jumlah * $kebutuhan->volume;
                    @endphp
                    <tr class="table-border">
                        <td class="table-border">{{ $i++ }}</td>

                        <td class="table-border">{{ $kebutuhan->uraian }}</td>
                        <td class="table-border">{{ $kebutuhan->volume }}</td>
                        <td class="table-border">{{ $kebutuhan->satuan }}</td>
                        <td class="table-border">{{ formatRupiah($kebutuhan->harga) }}</td>
                        <td class="table-border text-center">{{ $kebutuhan->jumlah }}</td>
                        <td class="table-border">{{ formatRupiah($total_satuan) }}</td>
                    </tr>
                @endforeach
                @if (count($kebutuhans['Bahan Pemeliharaan']) <= 0)
                    <tr>
                        <td colspan="7" class="text-center table-border">Data tidak ada</td>
                    </tr>
                @endif
                <tr class="border-none">

                    <td colspan="6" class="text-end fw-bold border-none">Total Biaya Pemeliharaan Potensi/ Produk
                        Unggulan Unit Usaha</td>
                    <td class="fw-bold">&nbsp;{{ formatRupiah($total_pemeliharaan) }}</td>
                </tr>
            </table>
        </div>
        <div class="isi mt-3">
            <p class="p-0"> &nbsp;&nbsp; Biaya Pembelian Bahan perminggu</p>
            <table class="table ">
                <tr class="text-center table-border">
                    <td class="table-border">NO</td>
                    <td class="table-border">Uraian</td>
                    <td class="table-border">Volume</td>
                    <td class="table-border">Satuan</td>
                    <td class="table-border">Harga Satuan</td>
                    <td class="table-border" class=" p-2">Jumlah <br>
                        Pengadaan <br>
                        pertahun</td>
                    <td class="table-border">Jumlah (Rp)</td>
                </tr>
                @php
                    $total_mingguan = 0;
                    $i = 1;
                @endphp
                @foreach ($kebutuhans['Pembiayaan-pembiayaan mingguan'] as $kebutuhan)
                    @php
                        $total_mingguan += $kebutuhan->harga * $kebutuhan->jumlah * $kebutuhan->volume;
                        $total_satuan = $kebutuhan->harga * $kebutuhan->jumlah * $kebutuhan->volume;
                    @endphp
                    <tr class="table-border">
                        <td class="table-border">{{ $i++ }}</td>

                        <td class="table-border">{{ $kebutuhan->uraian }}</td>
                        <td class="table-border">{{ $kebutuhan->volume }}</td>
                        <td class="table-border">{{ $kebutuhan->satuan }}</td>
                        <td class="table-border">{{ formatRupiah($kebutuhan->harga) }}</td>
                        <td class="table-border text-center">{{ $kebutuhan->jumlah }}</td>
                        <td class="table-border">{{ formatRupiah($total_satuan) }}</td>
                    </tr>
                @endforeach
                @if (count($kebutuhans['Pembiayaan-pembiayaan mingguan']) <= 0)
                    <tr>
                        <td colspan="7" class="text-center table-border">Data tidak ada</td>
                    </tr>
                @endif
                <tr class="border-none">

                    <td colspan="6" class="text-end fw-bold border-none">Total Biaya Pembelian Bahan perminggu</td>
                    <td class="fw-bold">&nbsp;{{ formatRupiah($total_mingguan) }}</td>
                </tr>
                <tr class="border-none">

                    <td colspan="6" class="text-end fw-bold border-none">TOTAL BIAYA MODAL PRODUKSI</td>
                    <td class="fw-bold">{{ formatRupiah($y) }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="isi mt-3 page-break">
        <p class="fw-bold ">C. &nbsp;&nbsp;BIAYA MODAL PEKERJA</p>
        <div class="isi">
            <table class="table ">
                <tr class="text-center table-border">
                    <td class="table-border">NO</td>
                    <td class="table-border">Uraian</td>
                    <td class="table-border">Volume</td>
                    <td class="table-border">Satuan</td>
                    <td class="table-border">Harga Satuan</td>
                    <td class="table-border" class=" p-2">Jumlah <br>
                        Pengadaan <br>
                        pertahun</td>
                    <td class="table-border">Jumlah (Rp)</td>
                </tr>
                @php
                    $total_pekerja = 0;
                    $i = 1;
                @endphp
                @foreach ($kebutuhans['Pekerja'] as $kebutuhan)
                    @php
                        $total_pekerja += $kebutuhan->harga * $kebutuhan->jumlah * $kebutuhan->volume;
                        $total_satuan = $kebutuhan->harga * $kebutuhan->jumlah * $kebutuhan->volume;
                    @endphp
                    <tr class="table-border">
                        <td class="table-border">{{ $i++ }}</td>

                        <td class="table-border">{{ $kebutuhan->uraian }}</td>
                        <td class="table-border">{{ $kebutuhan->volume }}</td>
                        <td class="table-border">{{ $kebutuhan->satuan }}</td>
                        <td class="table-border">{{ formatRupiah($kebutuhan->harga) }}</td>
                        <td class="table-border text-center">{{ $kebutuhan->jumlah }}</td>
                        <td class="table-border">{{ formatRupiah($total_satuan) }}</td>
                    </tr>
                @endforeach
                @if (count($kebutuhans['Pekerja']) <= 0)
                    <tr>
                        <td colspan="7" class="text-center table-border">Data tidak ada</td>
                    </tr>
                @endif
                <tr class="border-none">

                    <td colspan="6" class="text-end fw-bold border-none"> TOTAL BIAYA MODAL PEKERJA</td>
                    <td class="fw-bold">&nbsp;{{ formatRupiah($z) }}</td>
                </tr>
                <tr class="border-none">

                    <td colspan="6" rowspan="2" class="text-end fw-bold border-none"
                        style="padding-top: 20px">TOTAL
                        KESELURUHAN PENGELUARAN <br>
                        (Total Biaya Modal Awal + Produksi + Pekerja)</td>
                    <td class="fw-bold" rowspan="2">


                        &nbsp;{{ formatRupiah($total_pengeluaran) }}</td>
                </tr>

            </table>
        </div>
    </div>

    <p class="fw-bold p-0">2. &nbsp;&nbsp;PROYEKSI PENDAPATAN UNIT USAHA</p>
    <div class="isi">
        <table>
            <tr>
                <td> a Jumlah produksi /
                    panen&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td>:</td>
                <td></td>
            </tr>
            <tr>
                <td> b Jumlah produksi /
                    tahun&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td>:</td>
                <td></td>
            </tr>
        </table>
        <div class="isi">
            <table class="table ">
                <tr class="text-center table-border">
                    <td class="table-border">NO</td>
                    <td class="table-border">Kegiatan</td>
                    <td class="table-border">Volume</td>
                    <td class="table-border">Satuan</td>
                    <td class="table-border">Harga Satuan</td>
                    <td class="table-border" class=" p-2">Jumlah <br>
                        Pengadaan <br>
                        pertahun</td>
                    <td class="table-border">Jumlah (Rp)</td>
                </tr>
                @php
                    $total_pendapatan = 0;
                    $i = 1;
                @endphp
                @foreach ($penjualans as $penjualan)
                    @php
                        $total_pendapatan += $penjualan->harga * $penjualan->jumlah * $penjualan->volume;
                        $total_satuan = $penjualan->harga * $penjualan->jumlah * $penjualan->volume;
                    @endphp
                    <tr class="table-border">
                        <td class="table-border">{{ $i++ }}</td>
                        <td class="table-border">{{ $penjualan->kegiatan }}</td>
                        <td class="table-border">{{ $penjualan->volume }}</td>
                        <td class="table-border">{{ $penjualan->satuan }}</td>
                        <td class="table-border">{{ formatRupiah($penjualan->harga) }}</td>
                        <td class="table-border text-center">{{ $penjualan->jumlah }}</td>
                        <td class="table-border">{{ formatRupiah($total_satuan) }}</td>
                    </tr>
                @endforeach
                @if (count($penjualans) <= 0)
                    <tr>
                        <td colspan="7" class="text-center table-border">Data tidak ada</td>
                    </tr>
                @endif
                <tr class="border-none">

                    <td colspan="6" class="text-end fw-bold border-none"> Total Pendapatan</td>
                    <td class="fw-bold">
                        &nbsp;{{ formatRupiah($total_pendapatan) }}</td>
                </tr>
            </table>
        </div>
    </div>
    <p class="fw-bold p-0 mt-3">3. &nbsp;&nbsp; BIAYA PERKIRAAN ARUS KAS UNIT USAHA</p>
    <div class="isi">
        <p class="p-0"> Arus Kas Masuk</p>
        <div class="isi">
            <table class="table ">
                <tr class="text-center table-border">
                    <td class="table-border" rowspan="2">NO</td>
                    <td class="table-border" rowspan="2">Klasifikasi Modal</td>
                    <td class="table-border" colspan="3">Tahun ke</td>
                </tr>
                <tr>
                    <td class="table-border text-center">1</td>
                    <td class="table-border text-center">2</td>
                    <td class="table-border text-center">3</td>
                </tr>

                <tr class="table-border">
                    <td class="table-border">1</td>
                    <td class="table-border">Penyertaan Modal</td>
                    <td class="table-border"> {{ formatRupiah($manual) }} </td>
                    <td class="table-border"></td>
                    <td class="table-border"></td>
                </tr>
                <tr class="table-border">
                    <td class="table-border">2</td>
                    <td class="table-border">Sisa Kas Unit tahun lalu</td>
                    <td class="table-border"></td>
                    <td class="table-border">{{ formatRupiah($kas_unit) }}</td>
                    <td class="table-border">{{ formatRupiah($kas_bersih2) }}</td>
                </tr>
                <tr class="table-border">
                    <td class="table-border">3</td>
                    <td class="table-border">Pendapatan Unit Usaha</td>
                    <td class="table-border"></td>
                    <td class="table-border">{{ formatRupiah($total_pendapatan) }}</td>
                    <td class="table-border">{{ formatRupiah($unit_usaha) }}
                    </td>
                </tr>
                <tr class="border-none">

                    <td colspan="2" class="text-end fw-bold border-none">Total Arus Masuk &nbsp;</td>

                    <td class="fw-bold"> {{ formatRupiah($p1) }}</td>
                    <td class="fw-bold">{{ formatRupiah($p2) }}</td>
                    <td class="fw-bold">{{ formatRupiah($p3) }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="isi mt-3">
        <p class="p-0"> Arus Kas Keluar</p>
        <div class="isi">
            <table class="table ">
                <tr class="text-center table-border">
                    <td class="table-border" rowspan="2">NO</td>
                    <td class="table-border" rowspan="2">Klasifikasi Modal</td>
                    <td class="table-border" colspan="3">Tahun ke</td>
                </tr>
                <tr>
                    <td class="table-border text-center">1</td>
                    <td class="table-border text-center">2</td>
                    <td class="table-border text-center">3</td>
                </tr>

                <tr class="table-border">
                    <td class="table-border">1</td>
                    <td class="table-border">Modal Awal</td>
                    <td class="table-border">{{ formatRupiah($x) }}</td>
                    @php
                        $tahun2 = $x + ($akp->pembiayaan / 100) * $x;
                        $tahun3 = $tahun2 + ($akp->pembiayaan / 100) * $tahun2;
                    @endphp
                    <td class="table-border">{{ formatRupiah($tahun2) }}</td>
                    <td class="table-border">{{ formatRupiah($tahun3) }}</td>
                </tr>
                <tr class="table-border">
                    <td class="table-border">2</td>
                    <td class="table-border">Modal Produksi</td>
                    <td class="table-border">{{ formatRupiah($y) }}</td>
                    @php
                        $ytahun2 = $y + ($akp->pembiayaan / 100) * $y;
                        $ytahun3 = $ytahun2 + ($akp->pembiayaan / 100) * $ytahun2;
                    @endphp
                    <td class="table-border">{{ formatRupiah($ytahun2) }}</td>
                    <td class="table-border">{{ formatRupiah($ytahun3) }}</td>
                </tr>
                <tr class="table-border">
                    <td class="table-border">3</td>
                    <td class="table-border">Modal Pekerja</td>
                    <td class="table-border">{{ formatRupiah($z) }}</td>
                    @php
                        $ztahun2 = $z + ($akp->pembiayaan / 100) * $z;
                        $ztahun3 = $ztahun2 + ($akp->pembiayaan / 100) * $ztahun2;
                    @endphp
                    <td class="table-border">{{ formatRupiah($ztahun2) }}</td>
                    <td class="table-border">{{ formatRupiah($ztahun3) }}</td>
                </tr>
                <tr class="table-border">
                    <td class="table-border">4</td>
                    <td class="table-border">Pajak (0,5% x omset)</td>
                    <td class="table-border"></td>
                    <td class="table-border">{{ formatRupiah((0.5 / 100) * $total_pendapatan) }}</td>
                    <td class="table-border">{{ formatRupiah((0.5 / 100) * $unit_usaha) }}</td>
                </tr>
                <tr class="table-border">
                    <td class="table-border">5</td>
                    <td class="table-border">Lain-lain</td>
                    <td class="table-border"></td>
                    <td class="table-border"></td>
                    <td class="table-border"></td>
                </tr>
                <tr class="border-none">


                    <td colspan="2" class="text-end fw-bold border-none"> Total Arus Keluar</td>
                    <td class="fw-bold">&nbsp;{{ formatRupiah($q1) }}</td>
                    <td class="fw-bold">&nbsp;
                        {{ formatRupiah($q2) }}
                    </td>
                    <td class="fw-bold">&nbsp;
                        {{ formatRupiah($q3) }}
                    </td>

                </tr>
                <tr class="border-none">

                    <td colspan="2" class="text-end fw-bold border-none"> Arus Kas Bersih</td>
                    <td class="fw-bold">
                        &nbsp;{{ formatRupiah($kas_bersih1) }}
                    </td>
                    <td class="fw-bold">
                        &nbsp;
                        {{ formatRupiah($kas_bersih2) }}
                    </td>
                    <td class="fw-bold">&nbsp;
                        {{ formatRupiah($kas_bersih3) }}
                    </td>

                </tr>
            </table>
        </div>
    </div>
    <p class="fw-bold p-0 mt-3"> 4. &nbsp;&nbsp; PROYEKSI LABA RUGI UNIT USAHA</p>
    <div class="isi">

        <div class="isi">
            <table class="table ">
                <tr class="text-center table-border">
                    <td class="table-border" rowspan="2">NO</td>
                    <td class="table-border" rowspan="2">Klasifikasi Modal</td>
                    <td class="table-border" colspan="3">Tahun ke</td>
                </tr>
                <tr>
                    <td class="table-border text-center">1</td>
                    <td class="table-border text-center">2</td>
                    <td class="table-border text-center">3</td>
                </tr>
                <tr class="table-border">
                    @php
                        $total_pendapatan2 = $total_pendapatan + ($akp->pendapatan / 100) * $total_pendapatan;
                        $total_pendapatan3 = $total_pendapatan2 + ($akp->pendapatan / 100) * $total_pendapatan2;
                    @endphp
                    <td class="table-border">A</td>
                    <td class="table-border">Penjualan</td>
                    <td class="table-border">{{ formatRupiah($total_pendapatan) }}</td>
                    <td class="table-border">{{ formatRupiah($total_pendapatan2) }}</td>
                    <td class="table-border">{{ formatRupiah($total_pendapatan3) }}</td>
                </tr>
                <tr class="table-border">
                    @php
                        $total_pengeluaran2 = $total_pengeluaran + (5 / 100) * $total_pengeluaran;
                        $total_pengeluaran3 = $total_pengeluaran2 + ($akp->pendapatan / 100) * $total_pengeluaran2;
                    @endphp
                    <td class="table-border">B</td>
                    <td class="table-border">Biaya Modal Awal dan Produksi</td>
                    <td class="table-border">{{ formatRupiah($total_pengeluaran) }}</td>
                    <td class="table-border">{{ formatRupiah($total_pengeluaran2) }}</td>
                    <td class="table-border">{{ formatRupiah($total_pengeluaran3) }}</td>
                </tr>
                <tr class="table-border">

                    <td class="table-border">C</td>
                    <td class="table-border">Laba Usaha</td>
                    <td class="table-border">{{ formatRupiah($total_pendapatan - $total_pengeluaran) }}</td>
                    <td class="table-border">{{ formatRupiah($total_pendapatan2 - $total_pengeluaran2) }}</td>
                    <td class="table-border">{{ formatRupiah($total_pendapatan3 - $total_pengeluaran3) }}</td>
                </tr>
                <tr class="table-border">

                    <td class="table-border">D</td>
                    <td class="table-border">Bunga</td>
                    <td class="table-border"></td>
                    <td class="table-border"></td>
                    <td class="table-border"></td>
                </tr>
                <tr class="table-border">
                    @php
                        $laba_sebelum_pajak = $total_pendapatan + $total_pengeluaran;
                        $laba_sebelum_pajak2 = $total_pendapatan2 + $total_pengeluaran2;
                        $laba_sebelum_pajak3 = $total_pendapatan3 + $total_pengeluaran3;
                    @endphp
                    <td class="table-border">E</td>
                    <td class="table-border">Laba Sebelum Pajakn</td>
                    {{-- <td class="table-border">{{ formatRupiah($laba_sebelum_pajak) }}</td>
                    <td class="table-border">{{ formatRupiah($laba_sebelum_pajak2) }}</td>
                    <td class="table-border">{{ formatRupiah($laba_sebelum_pajak3) }}</td> --}}

                    @php
                        $sebelumPajak1 = $total_pendapatan - $total_pengeluaran;
                        $sebelumPajak2 = $total_pendapatan2 - $total_pengeluaran2;
                        $sebelumPajak3 = $total_pendapatan3 - $total_pengeluaran3;
                    @endphp

                    <td class="table-border">{{ formatRupiah($sebelumPajak1) }}</td>
                    <td class="table-border">{{ formatRupiah($sebelumPajak2) }}</td>
                    <td class="table-border">{{ formatRupiah($sebelumPajak3) }}</td>
                </tr>
                <tr class="table-border">
                    @php
                        $pajak = (0.5 / 100) * $total_pendapatan;
                        $pajak2 = (0.5 / 100) * $total_pendapatan2;
                        $pajak3 = (0.5 / 100) * $total_pendapatan3;
                    @endphp
                    <td class="table-border">F</td>
                    <td class="table-border">Pajak</td>
                    <td class="table-border">{{ formatRupiah($pajak) }}</td>
                    <td class="table-border">{{ formatRupiah($pajak2) }}</td>
                    <td class="table-border">{{ formatRupiah($pajak3) }}</td>
                </tr>
                <tr class="table-border">

                    <td class="table-border">G</td>
                    <td class="table-border">Laba Setelah Pajak</td>
                    <td class="table-border">{{ formatRupiah($sebelumPajak1 - $pajak) }}</td>
                    <td class="table-border">{{ formatRupiah($sebelumPajak2 - $pajak2) }}</td>
                    <td class="table-border">{{ formatRupiah($sebelumPajak3 - $pajak3) }}</td>
                </tr>
            </table>
        </div>
    </div>


</body>

</html>
