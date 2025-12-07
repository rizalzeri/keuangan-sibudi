@extends('layouts.main')

@section('container')
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

        .green-text {
            color: rgb(5, 192, 67);
        }

        .yellow-text {
            color: rgb(175, 173, 19);
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

        .fw-bold {
            font-weight: bold;
        }
    </style>
    <div class="card">
        <div class="card-body overflow-auto">
            <a href="/export-pdf/laporan-laba-rugi" class="btn btn-danger mt-3"><i class="bi bi-filetype-pdf"></i>
                PDF</a>
            <div class="card-title">2. LAPORAN LABA RUGI</div>


            <!-- Pendapatan Section -->
            <div class="report-section">
                <table class="table-report">
                    <tr>
                        <th colspan="4">1 PENDAPATAN UNIT USAHA</th>
                    </tr>
                    @foreach ($units as $unit)
                        <tr>
                            <td>{{ $unit->nm_unit }}</td>
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
                            <td>Biaya Ops {{ $unit->nm_unit }}</td>
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
                        <td>Gaji/Honor Pengurus</td>
                        <td class="text-end"></td>
                        <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno1'])) }}</td>
                    </tr>
                    <tr>
                        <td>ATK dan Fotocopy</td>
                        <td class="text-end"></td>
                        <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno2'])) }}</td>
                    </tr>
                    <tr>
                        <td>Rapat-rapat</td>
                        <td class="text-end"></td>
                        <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno3'])) }}</td>
                    </tr>
                    <tr>
                        <td>Akumulasi Penyusutan</td>
                        <td class="text-end"></td>
                        <td class="text-end red-text">{{ formatRupiah($akumulasi_penyusutan) }}</td>
                    </tr>
                    <tr>
                        <td>Lain-lain</td>
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
        </div>
    </div>
@endsection
