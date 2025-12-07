<style>
    body {
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
    }



    .table-report {
        width: 100%;
        border-collapse: collapse;
    }

    .table-report th,
    .table-report td {

        font-size: 16px
    }

    .table-report th {
        text-align: left;
    }

    .red-text {
        color: red;
    }

    .green-text {
        color: rgb(5, 192, 67);
    }

    .yellow-text {
        color: rgb(175, 173, 19);
    }

    .text-end {
        text-align: end;
    }

    .text-start {
        text-align: start;
    }

    .fw-bold {
        font-weight: bold;
    }

    .ms {
        margin-left: 20px
    }
</style>

<body>
    <div class="card">

        <table class="table-report">
            <tr>
                <th colspan="4">2. LAPORAN LABA RUGI</th>
            </tr>
            <tr>
                <th colspan="4">1 PENDAPATAN UNIT USAHA</th>
            </tr>
            @foreach ($units as $unit)
                <tr>
                    <td>
                        <span class="ms">{{ $unit->nm_unit }}</span>
                    </td>
                    <td class="text-end"></td>
                    <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['pu' . $unit->kode])) }}
                    </td>
                    <td></td>
                </tr>
            @endforeach

            <tr class="fw-bold border-bottom">
                <td colspan="2">Total Seluruh Pendapatan</td>
                <td class="text-end"></td>
                <td class="text-end red-text">{{ formatRupiah($pendapatanTahun['pu']) }}</td>
            </tr>

            <tr>
                <th colspan="4">2 BIAYA</th>
            </tr>
            @foreach ($units as $unit)
                <tr>
                    <td><span class="ms"> Biaya Ops {{ $unit->nm_unit }}</span></td>
                    <td class="text-end"></td>
                    <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bo' . $unit->kode])) }}
                    </td>

                </tr>
            @endforeach

            <tr class="fw-bold border-bottom">
                <td>Total Biaya Operasional</td>
                <td class="text-end"></td>
                <td class="text-end red-text">{{ formatRupiah($pendapatanTahun['bo']) }}</td>
            </tr>
            <tr>
                <td><span class="ms"> Gaji/Honor Pengurus </span></td>
                <td class="text-end"></td>
                <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno1'])) }}</td>
            </tr>
            <tr>
                <td><span class="ms"> ATK dan Fotocopy </span></td>
                <td class="text-end"></td>
                <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno2'])) }}</td>
            </tr>
            <tr>
                <td><span class="ms"> Rapat-rapat </span></td>
                <td class="text-end"></td>
                <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno3'])) }}</td>
            </tr>
            <tr>
                <td><span class="ms"> Akumulasi Penyusutan </span></td>
                <td class="text-end"></td>
                <td class="text-end red-text">{{ formatRupiah($akumulasi_penyusutan) }}</td>
            </tr>
            <tr>
                <td><span class="ms"> Lain-lain</span></td>
                <td class="text-end"></td>
                <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno4'])) }}</td>
            </tr>
            <tr class="fw-bold border-bottom">
                <td>Total Biaya Non Operasional</td>
                <td class="text-end"></td>
                <td class="text-end red-text">
                    {{ formatRupiah($pendapatanTahun['bno'] + $akumulasi_penyusutan) }}
                </td>
            </tr>
            <tr class="fw-bold border-bottom">
                <td colspan="2">Total Seluruh Biaya</td>
                <td class="text-end"></td>
                <td class="text-end red-text">{{ formatRupiah($akumulasiBiaya) }}</td>
            </tr>
            <tr class="fw-bold">
                <td colspan="2">
                    <p class="{{ $totalLabaRugi < 0 ? 'yellow-text' : 'green-text' }}">
                        Total {{ $totalLabaRugi < 0 ? 'Rugi' : 'Laba' }} Berjalan
                    </p>
                </td>
                <td class="text-end"></td>
                <td class="text-end red-text">{{ formatRupiah($totalLabaRugi) }}</td>
            </tr>
        </table>
    </div>

</body>
