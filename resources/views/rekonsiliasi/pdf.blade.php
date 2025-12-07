<style>
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
    }

    .table-report th {
        text-align: left;
    }

    .red-text {
        color: red;
    }

    .border-bottom {
        border-bottom: 1px solid black;
    }

    .text-end {
        text-align: end;
    }

    .text-start {
        text-align: start;
    }

    .text-center {
        text-align: center;
    }

    .fw-bold {
        font-weight: bold;
    }

    .input-group {
        margin-bottom: 5px
    }

    .text-red {
        color: red;
    }

    .card {
        border: 1px solid black;
        border-radius: 10px;
        padding: 20px
    }
</style>
<h2 class="text-center">Rekonsiliasi Kas</h2>
<div class="card overflow-auto">
    <div class="card-body">

        <div class="card-title">Rekonsiliasi Kas</div>


        <!-- Pendapatan Section -->
        <div class="report-section">

            <table class="table-report">
                <tr>
                    <td>Total Kas di Pembukuan</td>
                    <td></td>
                    <td>
                        <div class="input-group mb-3">
                            Rp {{ formatNomor($kas) }}
                        </div>
                    </td>
                </tr>

                <tr>
                    <th>Posisi Kas</th>
                    <th></th>
                    <th></th>

                </tr>

                @foreach ($rekonsiliasis as $index => $rekonsiliasi)
                    <tr>
                        <td>{{ $rekonsiliasi->posisi }}</td>
                        <td>
                            <div class="input-group mb-3">
                                Rp {{ formatNomor($rekonsiliasi->jumlah) }}
                            </div>
                        </td>
                        <td></td>
                    </tr>
                @endforeach



                <tr>
                    <td>Total Kas di masing-masing pos</td>
                    <td></td>
                    <td>
                        <div class="input-group mb-3">
                            Rp {{ formatNomor($rekonsiliasis->sum('jumlah')) }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Selisi</td>
                    <td></td>
                    <td>
                        <div class="input-group">
                            Rp {{ formatNomor($rekonsiliasis->sum('jumlah') - $kas) }}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        @if ($rekonsiliasis->sum('jumlah') - $kas != 0)
                            <div class="text-red">
                                + Perlu rekonsiliasi/ diperiksa ulang
                            </div>
                        @endif
                    </td>
                </tr>
            </table>

        </div>
    </div>
</div>
