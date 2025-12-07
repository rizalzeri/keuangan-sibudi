<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LAPORAN PENANGGUNG JAWABAN BUMDESA</title>
    <style>
        body {
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            margin-left: 1.5cm;
            margin-right: 1.5cm;
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

        .table-border,
        td,
        th {
            border: 1px solid;
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
    </style>

</head>

<body>

    <h3 class="judul">LEMBAR PENGESAHAN</h3>
    <h3 class="judul">LEMBAR PERTANGGUNGJAWABAN LAPORAN AKHIR TAHUN </h3>
    <h3 class="judul mb">{{ session('selected_year', date('Y')) }}</h3>

    <p class="align-justify mb">Laporan tahunan beserta laporan keuangan dan informasi lain dalam
        dokumen ini dibuat sesuai dengan keadaan sebenarnya oleh pelaksana
        operasional yang ditelaah oleh dewan pengawas dan penasihat dengan
        membubuhkan tanda tangan di bawah ini.</p>

    <p class="text-center mb">Pelaksana Opersional</p>

    <p class="text-center">{{ $profil->nm_direktur }}</p>
    <p class="text-center mb">Direktur BUMDesa</p>
    <p class="text-end">{{ $profil->kecamatan }}, ........................................</p>

    <table class="table table-border ">
        <tr>
            <td>
                <p class="text-center mb-100">Pengawas BUMDesa </p>
                <p class="text-center">{{ $profil->nm_pengawas }} </p>
            </td>
            <td>
                <p class="text-center mb-100">Penasehat BUMDesa</p>
                <p class="text-center">{{ $profil->nm_penasehat }} </p>
            </td>
        </tr>
    </table>
    {{-- Break --}}
    <div class="page-break"></div>
    @php
        $tambah = 0;
        $pades = 0;
        $lainya = 0;
        if (isset($ekuitas->akumulasi) and isset($ekuitas->pades) and isset($ekuitas->lainya)) {
            $tambah = $laba_berjalan * ($ekuitas->akumulasi / 100);
            $pades = $laba_berjalan * ($ekuitas->pades / 100);
            $lainya = $laba_berjalan * ($ekuitas->lainya / 100);
        }
        $modal_akhir = $ditahan + $tambah + $modal_desa + $modal_masyarakat + $modal_bersama;
    @endphp
    <p>A. IKHTISAR PENCAPAIAN SATU TAHUN</p>
    <div class="isi">

        <p class="align-justify p-0">
            {{-- Unit usaha BUMDesa {{ $profil->nm_bumdes }} pada tahun
            {{ session('selected_year', date('Y')) }}
            antara lain @foreach ($units as $unit)
                {{ $unit->nm_unit }},
            @endforeach. Bahwa kegiatan tersebut
            {{ $lpj->kegiatan_usaha }} dengan program kerja yang telah disepakati.
            Berikut kami sampaikan capaian BUMDesa {{ $profil->nm_bumdes }}
            selama satu tahun, bahwa total asset BUMDesa pada akhir tahun
            terhitung {{ formatRupiah($aktiva) }} total omset yang dihasilkan selama setahun
            sebesar {{ formatRupiah($pendapatanTahun['pu']) }} dengan laba/rugi sebesar
            {{ formatRupiah($laba_berjalan) }} dan
            kontribusi PADes yang diberikan sebesar {{ formatRupiah($pades) }} sehingga total
            pada tahun {{ date('Y') + 1 }} sebesar {{ formatRupiah($modal_akhir) }}
            Pada tahun {{ date('Y') }} {{ $lpj->penambahan_modal }} ada penambahan penyertaan
            modal desa.  --}}
            {!! $lpj->hasil_capaian !!}
        </p>
    </div>

    <p>B. LOPORAN MANAJEMEN</p>
    <div class="isi align-justify">
        <p class=" p-0">1. Laporan Direktur</p>

        <div class="isi">
            <p>{!! $lpj->kebijakan_strategi !!}</p>
            <p>{!! $lpj->tantangan_hambatan !!}</p>
            <p>{!! $lpj->apresiasi !!}</p>
        </div>
        <p class="text-end">{{ $profil->kecamatan }}, ........................................</p>
        <p class="text-end">Direktur BUMDesa</p>
        <p class="text-end" style="margin-bottom: 70px">{{ $profil->nm_bumdes }}</p>
        <p class="text-end">{{ $profil->nm_direktur }}</p>
    </div>
    <div class="align-justify">
        <div class="isi">
            <p class=" p-0">2. Laporan Pengawas</p>

            <div class="isi">
                <p>{!! $lpj->tugas_pengawasan !!}</p>
                <p>{!! $lpj->pandangan_pengawas !!}</p>
                <p>{!! $lpj->catatan_pengawas !!}</p>
                <p>{!! $lpj->rekomendasi_pengawas !!}</p>
            </div>
            <p class="text-end">{{ $profil->kecamatan }}, ........................................</p>
            <p class="text-end">Pengawas BUMDesa</p>
            <p class="text-end" style="margin-bottom: 70px">{{ $profil->nm_bumdes }}</p>
            <p class="text-end">{{ $profil->nm_pengawas }}</p>
        </div>
    </div>
    {{-- Break --}}
    <div class="page-break"></div>
    <p>C. PROFIL BUMDESA </p>
    <div class="isi align-justify">
        <p>1. Visi</p>
        <div class="isi">
            <p class="p-0">{!! $profil->visi !!}</p>
        </div>
        <p>2. Misi</p>
        <div class="isi p-0">
            <p class="p-0">{!! $profil->misi !!}</p>
        </div>

        <p>3. Struktur Organisasi BUMDesa</p>

        <div class="st-title">
            MUSYAWARAH DESA
        </div>
        <div class="garis-vertikal"></div>
        <div class="row">
            <div id="penasehat">
                <table class="table">
                    <tr>
                        <td class="text-center">PENASEHAT</td>
                    </tr>
                    <tr>
                        <td class="text-center">{{ $profil->nm_penasehat }}</td>
                    </tr>
                </table>
            </div>

            <div class="garis-horisontal">
            </div>
            <div id="pengawas">
                <table class="table">
                    <tr>
                        <td class="text-center">PENGAWAS</td>
                    </tr>
                    <tr>
                        <td class="text-center">{{ $profil->nm_pengawas }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="direktur">
            <table class="table">
                <tr>
                    <td class="text-center">DIREKTUR</td>
                </tr>
                <tr>
                    <td class="text-center">{{ $profil->nm_direktur }}</td>
                </tr>
            </table>
        </div>
        <div class="garis-vertikal"></div>
        <div class="row">
            <div id="penasehat">
                <table class="table">
                    <tr>
                        <td class="text-center">SEKERTARIS</td>
                    </tr>
                    <tr>
                        <td class="text-center">{{ $profil->nm_serkertaris }}</td>
                    </tr>
                </table>
            </div>

            <div class="garis-horisontal">
            </div>
            <div id="pengawas">
                <table class="table">
                    <tr>
                        <td class="text-center">BENDAHARA</td>
                    </tr>
                    <tr>
                        <td class="text-center">{{ $profil->nm_bendahara }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <table class="table">
            @php
                $i = 1;
            @endphp
            @foreach ($units as $unit)
                <tr>
                    <td style="width: 33%;">{{ $unit->kepala_unit }}</td>
                    <td style="width: 33%;" class="text-center">{{ $i++ }}</td>
                    <td style="width: 33%;">{{ $unit->nm_unit }}</td>
                </tr>
            @endforeach
        </table>



        <p>4. Struktur Penyertaan Modal BUMDesa</p>
        <table class="table datatable mb">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tahun</th>
                    <th scope="col">Sumber</th>
                    @can('referral')
                        <th scope="col">Simpanan Pokok</th>
                        <th scope="col">Simpanan Wajib</th>
                        <th scope="col">Simpanan Sukarela</th>
                    @else
                        <th scope="col">Modal Desa</th>
                        <th scope="col">Modal Masyarakat</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($modals as $modal)
                    <tr>
                        <th scope="row">{{ $i++ }}</th>
                        <td>{{ $modal->tahun }}</td>
                        <td>{{ $modal->sumber }}</td>
                        <td>{{ formatRupiah($modal->mdl_desa) }}</td>
                        <td>{{ formatRupiah($modal->mdl_masyarakat) }}</td>
                        @can('referral')
                            <td>{{ formatRupiah($modal->mdl_bersama) }}</td>
                        @endcan
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3">Akumulasi</td>
                    <td>{{ formatRupiah($modals->sum('mdl_desa')) }}</td>
                    <td>{{ formatRupiah($modals->sum('mdl_masyarakat')) }}</td>
                    @can('referral')
                        <td>{{ formatRupiah($modals->sum('mdl_bersama')) }}</td>
                    @endcan
                </tr>
            </tbody>
        </table>
        <p>5. Legalitas BUMDesa</p>
        <table style="border-collapse: collapse;">
            <tr>
                <td style="border: none;">Nomor Badan Hukum </td>
                <td style="border: none;"> : </td>
                <td style="border: none;">{{ $profil->no_badan }}</td>
            </tr>
            <tr>
                <td style="border: none;">Nomor Perdes </td>
                <td style="border: none;"> : </td>
                <td style="border: none;">{{ $profil->no_perdes }}</td>
            </tr>
            <tr>
                <td style="border: none;">Nomor SK Pengurus </td>
                <td style="border: none;"> : </td>
                <td style="border: none;">{{ $profil->no_sk }}</td>
            </tr>
            <tr>
                <td style="border: none;">Nomor Ijin Berusaha (NIB) </td>
                <td style="border: none;">:</td>
                <td style="border: none;">{{ $profil->no_nib }}</td>
            </tr>
        </table>
        {{-- Breack --}}
        <div class="page-break"></div>
        <p>D. KINERJA BUMDESA</p>
        <div class="isi">
            <p>1. Kuantitatif</p>
            <div class="isi">
                @include('proker.pdf.kuantitatif')
            </div>
            <div class="mb"></div>
            <p>2. Kualititif </p>
            <div class="isi">
                <p class="align-justify p-0">{!! $lpj->hasil_kinerja !!}</p>
            </div>
        </div>
        <p>E. PERMASALAHAN YANG MEMPENGARUHI USAHA</p>
        <div class="isi">
            <p class="align-justify p-0">{!! $lpj->permasalahan_usaha !!}</p>
        </div>

        <p>F. POTENSI, PELUANG DAN PROSPEK USAHA </p>
        <div class="isi">
            <p class="align-justify p-0">{!! $lpj->potensi_peluang !!}</p>
        </div>
        <p>G. STRATEGI DAN KEBIJAKAN TAHUN BERIKUTNYA</p>
        <div class="isi">
            <p class="align-justify p-0">{!! $lpj->strategi_kebijakan !!}</p>
        </div>

    </div>
</body>

</html>
