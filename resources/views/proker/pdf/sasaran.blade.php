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
            <td class="text-end">{{ formatRupiah(old('pu_total', $target?->proyeksi_omset ?? 0)) }}</td>
        </tr>

        @foreach ($units as $unit)
            <tr>
                <td></td>
                <td>{{ $unit->nm_unit }}</td>
                <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['pu' . $unit->kode] ?? [])) }}</td>
                <td class="text-end">
                    {{ formatRupiah(old('pu' . $unit->kode, json_decode($target?->omset ?? '{}', true)['pu' . $unit->kode] ?? 0)) }}
                </td>
            </tr>
        @endforeach

        <tr class="border-bottom">
            <td></td>
            <td>Total Omset</td>
            <td class="text-end red-text">{{ formatRupiah($pendapatanTahun['pu'] ?? 0) }}</td>
            <td class="text-end">{{ formatRupiah($target?->proyeksi_total_omset ?? 0) }}</td>
        </tr>

        <tr>
            <td>2</td>
            <td>Proyeksi Pembiayaan</td>
            <td></td>
            <td></td>
        </tr>
        @foreach ($units as $unit)
            <tr>
                <td></td>
                <td>Biaya Ops {{ $unit->nm_unit }}</td>
                <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bo' . $unit->kode] ?? [])) }}</td>
                <td class="text-end">
                    {{ formatRupiah(old('bo' . $unit->kode, json_decode($target?->pembiayaan ?? '{}', true)['bo' . $unit->kode] ?? 0)) }}
                </td>
            </tr>
        @endforeach

        <tr class="border-bottom">
            <td></td>
            <td>Total Biaya Operasional</td>
            <td class="text-end red-text">{{ formatRupiah($pendapatanTahun['bo'] ?? 0) }}</td>
            <td class="text-end">{{ formatRupiah($target?->proyeksi_total_biaya ?? 0) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Gaji/Honor Pengurus</td>
            <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno1'] ?? [])) }}</td>
            <td class="text-end">
                {{ formatRupiah(old('gaji', $target?->gaji ?? 0)) }}
            </td>
        </tr>
        <tr>
            <td></td>
            <td>ATK dan Fotocopy</td>
            <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno2'] ?? [])) }}</td>
            <td class="text-end">
                {{ formatRupiah(old('atk', $target?->atk ?? 0)) }}
            </td>
        </tr>
        <tr>
            <td></td>
            <td>Rapat-rapat</td>
            <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno3'] ?? [])) }}</td>
            <td class="text-end">
                {{ formatRupiah(old('rapat', $target?->rapat ?? 0)) }}
            </td>
        </tr>
        <tr>
            <td></td>
            <td>Akumulasi Penyusutan</td>
            <td class="text-end red-text">{{ formatRupiah($akumulasi_penyusutan ?? 0) }}</td>
            <td class="text-end">
                {{ formatRupiah(old('penyusutan', $target?->penyusutan ?? 0)) }}
            </td>
        </tr>
        <tr>
            <td></td>
            <td>Lain-lain</td>
            <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno4'] ?? [])) }}</td>
            <td class="text-end">
                {{ formatRupiah(old('lain', $target?->lain ?? 0)) }}
            </td>
        </tr>

        @php

            $biaya = 0;
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


        <tr>
            <td>3</td>
            <td>Proyeksi {{ $totalLabaRugi < 0 ? 'Rugi' : 'Laba' }}</td>
            <td class="text-end red-text">{{ formatRupiah($totalLabaRugi ?? 0) }}</td>
            <td class="text-end">
                {{ formatRupiah(old('laba', $target?->laba ?? 0)) }}
            </td>
        </tr>
        <tr>
            <td>4</td>
            <td>Proyeksi Aset</td>
            <td class="text-end red-text">{{ formatRupiah($aktiva ?? 0) }}</td>
            <td class="text-end">
                {{ formatRupiah(old('aset', $target?->aset ?? 0)) }}
            </td>
        </tr>
        <tr>
            <td>5</td>
            <td>Proyeksi Pades</td>
            <td class="text-end red-text">{{ formatRupiah($pades ?? 0) }}</td>
            <td class="text-end">
                {{ formatRupiah(old('pades', $target?->pades ?? 0)) }}
            </td>
        </tr>
    </tbody>
</table>
