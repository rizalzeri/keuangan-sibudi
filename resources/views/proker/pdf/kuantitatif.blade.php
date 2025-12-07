 <table class="table table-bordered">
     <thead>
         <tr>
             <th>No</th>
             <th>Indikator</th>
             <th>Sasaran Kinerja</th>
             <th>Capaian Tahun <br> {{ date('Y') }}</th>
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
                 $omset = json_decode($target_lalu->omset ?? '{}', true)['pu' . $unit->kode] ?? 0;
                 $totalOmset += intval($omset);
             @endphp
             <tr>
                 <td></td>
                 <td>{{ $unit->nm_unit }}</td>
                 <td class="text-end">
                     {{ formatRupiah(old('pu' . $unit->kode, $omset)) }}
                 </td>
                 <td class="text-end red-text">
                     {{ formatRupiah(array_sum($pendapatan['pu' . $unit->kode] ?? [])) }}
                 </td>
             </tr>
         @endforeach


         <tr class="border-bottom">
             <td></td>
             <td>Total Omset</td>
             <td class="text-end">{{ formatRupiah($totalOmset) }}</td>
             <td class="text-end red-text">{{ formatRupiah($pendapatanTahun['pu']) }}</td>
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
                 $pembiayaan = json_decode($target_lalu->pembiayaan ?? '{}', true)['bo' . $unit->kode] ?? 0;
                 $pembiayaans = intval($pembiayaan);
                 $biaya += $pembiayaans;
             @endphp
             <tr>
                 <td></td>
                 <td>Biaya Ops {{ $unit->nm_unit }}</td>
                 <td class="text-end">
                     {{ formatRupiah(old('bo' . $unit->kode, json_decode($target_lalu->pembiayaan ?? '{}', true)['bo' . $unit->kode] ?? 0)) }}
                 </td>
                 <td class="text-end red-text">
                     {{ formatRupiah(array_sum($pendapatan['bo' . $unit->kode] ?? [])) }}
                 </td>
             </tr>
         @endforeach

         <tr class="border-bottom">
             <td></td>
             <td>Total Biaya Operasional</td>
             <td class="text-end">{{ formatRupiah($biaya) }}</td>
             <td class="text-end red-text">{{ formatRupiah($pendapatanTahun['bo'] ?? 0) }}</td>
         </tr>

         <tr>
             <td></td>
             <td>Gaji/Honor Pengurus</td>
             <td class="text-end">{{ formatRupiah(old('gaji', $target_lalu->gaji ?? 0)) }}</td>
             <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno1'] ?? [])) }}</td>
         </tr>
         <tr>
             <td></td>
             <td>ATK dan Fotocopy</td>
             <td class="text-end">{{ formatRupiah(old('atk', $target_lalu->atk ?? 0)) }}</td>
             <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno2'] ?? [])) }}</td>
         </tr>
         <tr>
             <td></td>
             <td>Rapat-rapat</td>
             <td class="text-end">{{ formatRupiah(old('rapat', $target_lalu->rapat ?? 0)) }}</td>
             <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno3'] ?? [])) }}</td>
         </tr>
         <tr>
             <td></td>
             <td>Akumulasi Penyusutan</td>
             <td class="text-end">{{ formatRupiah(old('penyusutan', $target_lalu->penyusutan ?? 0)) }}</td>
             <td class="text-end red-text">{{ formatRupiah($akumulasi_penyusutan ?? 0) }}</td>
         </tr>
         <tr>
             <td></td>
             <td>Lain-lain</td>
             <td class="text-end">{{ formatRupiah(old('lain', $target_lalu->lain ?? 0)) }}</td>
             <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno4'] ?? [])) }}</td>
         </tr>

         @php

             $biaya_non = 0;
             $biaya_non =
                 old('gaji', $target_lalu->gaji ?? 0) +
                 old('atk', $target_lalu->atk ?? 0) +
                 old('penyusutan', $target_lalu->penyusutan ?? 0) +
                 old('rapat', $target_lalu->rapat ?? 0) +
                 old('lain', $target_lalu->lain ?? 0);

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
             <td class="text-end">{{ formatRupiah($biaya_non) }}</td>
             <td class="text-end red-text">{{ formatRupiah($biaya_tahun) }}</td>
         </tr>

         <tr class="border-bottom">
             <td></td>
             <td>Total Biaya</td>
             <td class="text-end">{{ formatRupiah($total) }}</td>
             <td class="text-end red-text">{{ formatRupiah($biaya_tahun + $pendapatanTahun['bo']) }}</td>
         </tr>


         <tr>
             <td>3</td>
             <td>

                 Proyeksi Laba/Rugi

             </td>
             <td class="text-end">{{ formatRupiah(old('laba', $target_lalu->laba ?? 0)) }}</td>
             <td class="text-end red-text">{{ formatRupiah($totalLabaRugi ?? 0) }}</td>
         </tr>
         <tr>
             <td>4</td>
             <td>Proyeksi Aset</td>
             <td class="text-end">{{ formatRupiah(old('aset', $target_lalu->aset ?? 0)) }}</td>
             <td class="text-end red-text">{{ formatRupiah($aktiva) }}</td>
         </tr>
         <tr>
             <td>5</td>
             <td>Proyeksi Pades</td>
             <td class="text-end">{{ formatRupiah(old('pades', $target_lalu->pades ?? 0)) }}</td>
             <td class="text-end red-text">{{ formatRupiah($pades ?? 0) }}</td>
         </tr>
     </tbody>
 </table>
