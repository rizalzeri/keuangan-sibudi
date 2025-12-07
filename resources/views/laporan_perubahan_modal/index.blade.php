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

        .fw-bold {
            font-weight: bold;
        }
    </style>
    <div class="card overflow-auto">
        <div class="card-body">
            <a href="/export-pdf/laporan-perubahan-modal" class="btn btn-danger mt-3"><i class="bi bi-filetype-pdf"></i>
                PDF</a>
            <div class="card-title">4. LAPORAN PERUBAHAN MODAL</div>


            <!-- Pendapatan Section -->
            <div class="report-section">

                <form action="/laporan-keuangan/laporan-perubahan-modal/{{ $ekuitas->id }}" method="POST">
                    @method('PUT')

                    @csrf
                    <table class="table-report">
                        @can('referral')
                            <tr>
                                <td colspan="2"> Simpanan Pokok</td>
                                <td class="text-end"></td>
                                <td class="text-end red-text">{{ formatRupiah($modal_desa) }}</td>
                            </tr>
                            <tr class="">
                                <td colspan="2">Simpanan Wajib</td>
                                <td class="text-end"></td>
                                <td class="text-end red-text">{{ formatRupiah($modal_masyarakat) }}</td>
                            </tr>
                            <tr class="">
                                <td colspan="2">Simpanan Sukarela</td>
                                <td class="text-end"></td>
                                <td class="text-end red-text">{{ formatRupiah($modal_bersama) }}</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="2">Penyertaan modal desa</td>
                                <td class="text-end"></td>
                                <td class="text-end red-text">{{ formatRupiah($modal_desa) }}</td>
                            </tr>
                            <tr class="">
                                <td colspan="2">Penyertaan modal masyarakat</td>
                                <td class="text-end"></td>
                                <td class="text-end red-text">{{ formatRupiah($modal_masyarakat) }}</td>
                            </tr>
                        @endcan



                        <tr>
                            <td colspan="2">{{ $ditahan < 0 ? 'Rugi' : 'Laba' }} ditahan </td>
                            <td class="text-end"></td>
                            <td class="text-end text-danger">{{ formatRupiah($ditahan) }}</td>

                        </tr>

                        <tr>
                            <td>Laba Berjalan</td>
                            <td class="text-end d-flex  justify-content-end"><input type="number" placeholder="Tahun"
                                    class="form-control" name="tahun" style="width: 50%"
                                    value="{{ !isset($ekuitas->tahun) ? session('selected_year', date('Y')) : old('tahun', $ekuitas) }}">
                            </td>
                            <td class="text-end red-text">{{ formatRupiah($laba_berjalan) }}</td>

                        </tr>
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
                        <tr class="">

                            <td>
                                <span class="ms-5"> Tambah Modal</span>
                            </td>
                            <td class="text-end d-flex  justify-content-end">
                                <div class="input-group" style="width: 50%">
                                    <input type="number" class="form-control" placeholder="...%" name="akumulasi"
                                        value="{{ old('akumulasi', $ekuitas->akumulasi) }}">
                                    <span class="input-group-text" id="basic-addon2">%</span>
                                </div>
                            </td>
                            <td class="text-end"></td>
                            <td class="text-end red-text">{{ formatRupiah($tambah) }}</td>
                        </tr>
                        <tr>
                            <td> <span class="ms-5"> @can('referral')
                                        Laba dibagi
                                    @else
                                        PADes
                                    @endcan
                                </span></td>
                            <td class="text-end d-flex  justify-content-end">
                                <div class="input-group" style="width: 50%">
                                    <input type="number" placeholder="...%" class="form-control" name="pades"
                                        value="{{ old('pades', $ekuitas->pades) }}" style="width: 50%">
                                    <span class="input-group-text" id="basic-addon2">%</span>
                                </div>
                            </td>
                            <td class="text-end red-text">{{ formatRupiah($pades) }}</td>
                            <td class="text-end"></td>

                        </tr>
                        <tr>
                            <td> <span class="ms-5"> @can('referral')
                                        Dana Cadangan
                                    @else
                                        Lain Lain
                                    @endcan
                                </span></td>
                            <td class="text-end d-flex  justify-content-end">
                                <div class="input-group" style="width: 50%">
                                    <input type="number" placeholder="...%" class="form-control" name="lainya"
                                        value="{{ old('lainya', $ekuitas->lainya) }}" style="width: 50%">
                                    <span class="input-group-text" id="basic-addon2">%</span>
                                </div>
                            </td>
                            <td class="text-end red-text">{{ formatRupiah($lainya) }} </td>
                            <td class="text-end"></td>

                        </tr>
                        <tr>
                            <td>Modal Akhir 1 Januari</td>
                            <td class=""> {{ $ekuitas->tahun + 1 }}</td>
                            <td class="text-end"></td>
                            <td class="text-end red-text">{{ formatRupiah($modal_akhir) }}</td>
                        </tr>

                    </table>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
                <a href="/laporan-keuangan/laporan-perubahan-modal/ditahan/{{ $ekuitas->id }}" class="btn btn-dark mt-3"
                    onclick="return confirm('Yakin tambahkan ke laba ditahan dan hutang')">Tambah
                    laba ditahan dan hutang</a>
            </div>
        </div>
    </div>
@endsection
