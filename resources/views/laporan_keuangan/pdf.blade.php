<style>
    body {
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
    }

    table {
        width: 100%;
    }

    .text-end {
        text-align: right;
        font-size: 16px
    }

    .font-size {
        font-size: 16px
    }

    #tandatangan tr td {

        border: 1px solid black;
        vertical-align: bottom;
    }

    #tandatangan {

        border-collapse: collapse;
        margin-top: 50px;
        font-size: 16px
    }

    .border-laporan {
        border: 1px solid black;
        margin-bottom: 5px;
    }

    .page-break {
        page-break-after: always;
    }

    .text-center {
        text-align: center;
    }

    .fw {
        font-weight: bold
    }
</style>

<table>
    <tr>
        <td colspan="2" class="font-size fw">
            H. Laporan Keuangan
        </td>
    </tr>
    <tr>
        <td class="font-size">
            <p style="padding: 0">{{ unitUsaha()['nm_bumdes'] }}</p>
        </td>

        <td class="text-end">
            Per 31 Desember {{ session('selected_year', date('Y')) }}
        </td>
    </tr>
</table>

<div class="border-laporan">
    @include('neraca.pdf')
</div>
<div class="border-laporan">
    @include('laporan_laba_rugi.pdf')
</div>
<!-- Halaman baru untuk total -->
<div class="page-break"></div>
<div class="border-laporan">
    @include('laporan_arus_kas.pdf')
</div>
<div class="border-laporan">
    @include('laporan_perubahan_modal.pdf')
</div>

<table>
    <tr>
        <td class="text-end">
            {{ $nm_desa }}
        </td>
        <td class="text-end">
            {{ session('selected_year', date('Y') + 1) }}
        </td>
    </tr>
</table>
<table id="tandatangan">
    <tr>
        <td class="fw">Ditelaah</td>
        <td class="text-center">Tanda Tangan</td>
        <td class="fw">Dibuat</td>
        <td class="text-center">Tanda Tangan</td>
    </tr>
    <tr>
        <td>
            <p>Pengawas
                @can('referral')
                    Koperasi
                @else
                    BUMDesa
                @endcan
            </p>
            <p>{{ unitUsaha()['nm_pengawas'] }}</p>
        </td>
        <td class="text-center">(..............................)</td>
        <td>
            <p>Bendahara @can('referral')
                    Koperasi
                @else
                    BUMDesa
                @endcan
            </p>
            <p>{{ unitUsaha()['nm_bendahara'] }}</p>
        </td>
        <td class="text-center">(..............................)</td>
    </tr>
    <tr>
        <td>
            <p>Penasehat @can('referral')
                    Koperasi
                @else
                    BUMDesa
                @endcan
            </p>
            <p>{{ unitUsaha()['nm_penasehat'] }}</p>
        </td>
        <td class="text-center">(..............................)</td>
        <td>
            <p>Direktur @can('referral')
                    Koperasi
                @else
                    BUMDesa
                @endcan
            </p>
            <p>{{ unitUsaha()['nm_direktur'] }}</p>
        </td>
        <td class="text-center">(..............................)</td>
    </tr>
</table>
