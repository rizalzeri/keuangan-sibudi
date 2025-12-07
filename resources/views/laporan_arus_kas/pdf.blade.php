<style>
    body {
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
    }


    .report-section {
        margin-top: 20px;
    }

    .table-report {
        width: 100%;
        border-collapse: collapse;
    }

    .table-report th,
    .table-report td {
        padding: 5px 10px;
        font-size: 16px
    }

    .table-report th {
        text-align: left;
    }

    .red-text {
        color: red;
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
                <td class="fw-bold">3. LAPORAN ARUS KAS</td>
            </tr>

            <tr class="fw-bold border-bottom">
                <td colspan="2">Kas Awal (1 Januari)</td>
                <td class="text-end">2024</td>
                <td class="text-end red-text red-text">
                    {{ formatRupiah($saldo) }}
                </td>
            </tr>


            <tr>
                <td><span class="ms">Kas masuk operasional</span></td>
                <td class="text-end"></td>
                <td class="text-end red-text">
                    {{ formatRupiah($masuk->where('jenis_dana', 'operasional')->sum('nilai')) }}
                </td>

            </tr>
            <tr>
                <td><span class="ms">Kas masuk investasi</span></td>
                <td class="text-end"></td>
                <td class="text-end red-text">
                    {{ formatRupiah($masuk->where('jenis_dana', 'investasi')->sum('nilai')) }}
                </td>

            </tr>
            <tr>
                <td><span class="ms">Kas masuk pendanaan</span></td>
                <td class="text-end"></td>
                <td class="text-end red-text">
                    {{ formatRupiah($masuk->where('jenis_dana', 'pendanaan')->sum('nilai')) }}
                </td>

            </tr>
            <tr class="fw-bold border-bottom">
                <td>Total uang masuk</td>
                <td class="text-end"></td>
                <td class="text-end red-text">{{ formatRupiah($masuk->sum('nilai')) }}
                </td>
            </tr>
            <tr>
                <td><span class="ms">Kas keluar operasional</span></td>
                <td class="text-end"></td>
                <td class="text-end red-text">
                    {{ formatRupiah($keluar->where('jenis_dana', 'operasional')->sum('nilai')) }}
                </td>

            </tr>
            <tr>
                <td><span class="ms">Kas keluar investasi</span></td>
                <td class="text-end"></td>
                <td class="text-end red-text">
                    {{ formatRupiah($keluar->where('jenis_dana', 'investasi')->sum('nilai')) }}
                </td>

            </tr>
            <tr>
                <td><span class="ms">Kas keluar pendanaan</td>
                <td class="text-end"></td>
                <td class="text-end red-text">
                    {{ formatRupiah($keluar->where('jenis_dana', 'pendapatan')->sum('nilai')) }}
                </td>

            </tr>
            <tr class="fw-bold border-bottom">
                <td>Total uang keluar</td>
                <td class="text-end"></td>
                <td class="text-end red-text">{{ formatRupiah($keluar->sum('nilai')) }}
                </td>
            </tr>
            <tr class="fw-bold border-bottom">
                <td colspan="2">Perubahan Kas</td>
                <td class="text-end"></td>
                <td class="text-end red-text red-text">{{ formatRupiah($perubahan_kas) }}</td>
            </tr>
            <tr class="fw-bold">
                <td colspan="2">Kas Akhir</td>
                <td class="text-end"></td>
                <td class="text-end red-text red-text">
                    {{ formatRupiah($kas_akhir) }}
                </td>
            </tr>
        </table>
    </div>
</body>
