@extends('layouts_proker.main')

@section('container')
    @php
        $tambah = 0;
        $pades = 0;
        $lainya = 0;
        if (isset($ekuitas->akumulasi) and isset($ekuitas->pades) and isset($ekuitas->lainya)) {
            $tambah = $laba_berjalan * ($ekuitas->akumulasi / 100);
            $pades = $laba_berjalan * ($ekuitas->pades / 100);
            $lainya = $laba_berjalan * ($ekuitas->lainya / 100);
        }
        $modal_akhir = $tambah + $modal_desa + $modal_masyarakat;
    @endphp
    <p>1. Kuantitatif</p>
    <form action="/proker/sasaran/{{ $proker->id }}" method="POST">
        @csrf
        @method('PUT')
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th style="vertical-align:top;">No</th>
                    <th style="vertical-align:top;">Indikator</th>
                    <th>Capaian Tahun
                        <br>
                        {{ session('selected_year', date('Y')) }}
                    </th>
                    <th>Proyeksi <br>{{ session('selected_year', date('Y')) + 1 }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Proyeksi Omset</td>
                    <td></td>
                    <td></td>
                </tr>
                @php
                    $totalOmset = 0;
                @endphp

                @foreach ($units as $unit)
                    @php
                        $omset = json_decode($target->omset ?? '{}', true)['pu' . $unit->kode] ?? 0;
                        $totalOmset += intval($omset);
                    @endphp
                    <tr>
                        <td></td>
                        <td>{{ $unit->nm_unit }}</td>
                        <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['pu' . $unit->kode])) }}</td>
                        <td class="text-end">
                            <input class="form-control text-end" type="text" name="{{ 'pu' . $unit->kode }}"
                                value="{{ old('pu' . $unit->kode, json_decode($target->omset, true)['pu' . $unit->kode] ?? '') }}">
                        </td>
                    </tr>
                @endforeach

                <tr class="border-bottom">
                    <td></td>
                    <td>Total Omset</td>
                    <td class="text-end red-text">{{ formatRupiah($pendapatanTahun['pu']) }}</td>
                    <td class="text-end">{{ formatRupiah($totalOmset) }}</td>
                </tr>

                <tr>
                    <td>2</td>
                    <td>Proyeksi Pembiayaan </td>
                    <td></td>
                    <td></td>
                </tr>
                @php
                    $biaya = 0;

                @endphp

                @foreach ($units as $unit)
                    @php
                        $pembiayaan = json_decode($target->pembiayaan ?? '{}', true)['bo' . $unit->kode] ?? 0;
                        $biaya += $pembiayaan;

                    @endphp
                    <tr>
                        <td></td>
                        <td>Biaya Ops {{ $unit->nm_unit }}</td>
                        <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bo' . $unit->kode])) }}</td>
                        <td class="text-end">
                            <input class="form-control text-end" type="text" name="{{ 'bo' . $unit->kode }}"
                                value="{{ old('bo' . $unit->kode, json_decode($target->pembiayaan, true)['bo' . $unit->kode] ?? '') }}">
                        </td>
                    </tr>
                @endforeach

                <tr class="border-bottom">
                    <td></td>
                    <td>Total Biaya Operasional</td>
                    <td class="text-end red-text">{{ formatRupiah($pendapatanTahun['bo']) }}</td>
                    <td class="text-end">{{ formatRupiah($biaya) }}</td>
                </tr>

                <!-- Input lainnya -->
                <tr>
                    <td></td>
                    <td>Gaji/Honor Pengurus</td>
                    <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno1'])) }}</td>
                    <td class="text-end">
                        <input class="form-control text-end" type="text" name="gaji"
                            value="{{ old('gaji', $target->gaji) }}">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>ATK dan Fotocopy</td>
                    <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno2'])) }}</td>
                    <td class="text-end">
                        <input class="form-control text-end" type="text" name="atk"
                            value="{{ old('atk', $target->atk) }}">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Rapat-rapat</td>
                    <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno3'])) }}</td>
                    <td class="text-end">
                        <input class="form-control text-end" type="text" name="rapat"
                            value="{{ old('rapat', $target->rapat) }}">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Akumulasi Penyusutan</td>
                    <td class="text-end red-text">{{ formatRupiah($akumulasi_penyusutan) }}</td>
                    <td class="text-end">
                        <input class="form-control text-end" type="text" name="penyusutan"
                            value="{{ old('penyusutan', $target->penyusutan) }}">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Lain-lain</td>
                    <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno4'])) }}</td>
                    <td class="text-end">
                        <input class="form-control text-end" type="text" name="lain"
                            value="{{ old('lain', $target->lain) }}">
                    </td>
                </tr>

                @php

                    $biaya_non = 0;
                    $biaya_non =
                        old('gaji', $target->gaji) +
                        old('atk', $target->atk) +
                        old('penyusutan', $target->penyusutan) +
                        old('penyusutan', $target->rapat) +
                        old('lain', $target->lain);

                    $biaya_tahun =
                        array_sum($pendapatan['bno1']) +
                        array_sum($pendapatan['bno2']) +
                        array_sum($pendapatan['bno3']) +
                        $akumulasi_penyusutan +
                        array_sum($pendapatan['bno4']);

                    $total = $biaya + $biaya_non;
                @endphp

                <tr class="border-bottom">
                    <td></td>
                    <td>Total Biaya Non Operasional</td>
                    <td class="text-end red-text">{{ formatRupiah($biaya_tahun) }}</td>
                    <td class="text-end">{{ formatRupiah($biaya_non) }}</td>
                </tr>

                <tr class="border-bottom">
                    <td></td>
                    <td>Total Biaya</td>
                    <td class="text-end red-text">{{ formatRupiah($biaya_tahun + $pendapatanTahun['bo']) }}</td>
                    <td class="text-end">{{ formatRupiah($total) }}</td>
                </tr>

                <!-- Proyeksi lainnya -->
                <tr>
                    <td>3</td>
                    <td>
                        <p class="{{ $totalLabaRugi < 0 ? 'yellow-text' : 'green-text' }}">
                            Proyeksi Laba
                        </p>
                    </td>
                    <td class="text-end red-text">{{ formatRupiah($totalLabaRugi) }}</td>
                    <td class="text-end">
                        <input class="form-control text-end" type="text" name="laba"
                            value="{{ old('laba', $target->laba) }}">
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Proyeksi Aset</td>
                    <td class="text-end red-text">{{ formatRupiah($aktiva) }}</td>
                    <td class="text-end">
                        <input class="form-control text-end" type="text" name="aset"
                            value="{{ old('aset', $target->aset) }}">
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Proyeksi Pades</td>
                    <td class="text-end red-text">{{ formatRupiah($pades) }}</td>
                    <td class="text-end">
                        <input class="form-control text-end" type="text" name="pades"
                            value="{{ old('pades', $target->pades) }}">
                    </td>
                </tr>
            </tbody>
        </table>
    @endsection
</form>
