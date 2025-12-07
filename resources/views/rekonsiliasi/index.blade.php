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
    </style>
    <div class="card overflow-auto">
        <div class="card-body">

            <div class="card-title">Rekonsiliasi Kas</div>
            <div class="col">
                <a href="/rekonsiliasi/create" class="btn btn-success btn-sm">Tambah Posisi</a>


            </div>
            <div class="col text-end">
                <a href="/export-pdf/rekonsiliasi" class="btn btn-danger btn-sm"><i class="bi bi-filetype-pdf"></i> PDF</a>
            </div>
            <!-- Pendapatan Section -->
            <div class="report-section">
                <form action="/rekonsiliasi/update" method="POST">
                    @csrf
                    <table class="table-report">
                        <tr>
                            <td class="pb-4">Total Kas di Pembukuan</td>
                            <td></td>
                            <td>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Rp</span>
                                    <input type="text" class="form-control text-end" aria-label="Sizing example input"
                                        aria-describedby="inputGroup-sizing-default" value="{{ formatNomor($kas) }}"
                                        disabled>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <th>Posisi Kas</th>
                            <th></th>
                            <th></th>

                        </tr>
                        @csrf {{-- Untuk melindungi form dari CSRF attack --}}
                        @foreach ($rekonsiliasis as $index => $rekonsiliasi)
                            <tr>
                                <td>{{ $rekonsiliasi->posisi }}</td>
                                <td>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="inputGroup-sizing-default">Rp</span>
                                        <input type="text" class="form-control text-end"
                                            name="rekonsiliasi[{{ $index }}][jumlah]" {{-- Set field sebagai array --}}
                                            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"
                                            value="{{ formatNomor($rekonsiliasi->jumlah) }}"
                                            onkeyup="onlyNumberAmount(this)">
                                        <input type="hidden" name="rekonsiliasi[{{ $index }}][id]"
                                            value="{{ $rekonsiliasi->id }}"> {{-- Hidden field untuk ID --}}
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
                                    <span class="input-group-text" id="inputGroup-sizing-default">Rp</span>
                                    <input type="text" class="form-control text-end" aria-label="Sizing example input"
                                        aria-describedby="inputGroup-sizing-default"
                                        value="{{ formatNomor($rekonsiliasis->sum('jumlah')) }}" disabled>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Selisi</td>
                            <td></td>
                            <td>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Rp</span>
                                    <input type="text" class="form-control text-end" aria-label="Sizing example input"
                                        aria-describedby="inputGroup-sizing-default"
                                        value="{{ formatNomor($rekonsiliasis->sum('jumlah') - $kas) }}" disabled>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                @if ($rekonsiliasis->sum('jumlah') - $kas != 0)
                                    <div class="text-danger">
                                        + Perlu rekonsiliasi/ diperiksa ulang
                                    </div>
                                @endif
                            </td>
                        </tr>
                    </table>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
