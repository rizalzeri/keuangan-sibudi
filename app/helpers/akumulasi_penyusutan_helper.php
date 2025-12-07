<?php


// if (!function_exists('akumulasiPenyusutan')) {
//     function akumulasiPenyusutan($asets)
//     {
//         $akumulasi = 0;
//         $investasi = 0;
//         $currentMonth = date('m'); // Mendapatkan bulan saat ini (format angka 2 digit)

//         foreach ($asets as $aset) {
//             if ($aset->wkt_ekonomis != 0) {
//                 $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
//             } else {
//                 $penyusutan = 0;
//             }

//             // Hitung nilai aset saat ini berdasarkan masa pakai dan penyusutan
//             $saat_ini = $aset->nilai - masaPakai($aset->created_at, $aset->wkt_ekonomis)['masa_pakai'] * $penyusutan;

//             // Periksa apakah bulan saat ini adalah antara Oktober hingga Desember
//             if ($currentMonth >= 1 && $currentMonth <= 4) {
//                 // Jika Oktober hingga Desember, set akumulasi penyusutan menjadi 0
//                 $akumulasi = 0;
//             } else {
//                 // Jika belum mencapai masa ekonomis penuh, tambahkan penyusutan ke akumulasi
//                 if (masaPakai($aset->created_at, $aset->wkt_ekonomis)['tahun'] < $aset->wkt_ekonomis) {
//                     $akumulasi += $penyusutan;
//                 }
//             }

//             // Tambahkan nilai saat ini ke investasi
//             $investasi += $saat_ini;
//         }

//         return ['inven' => $investasi, 'akumu' => $akumulasi];
//     }
// }


// if (!function_exists('akumulasiPenyusutanIventasi')) {
//     function sakumulasiPenyusutanIventasi($asets)
//     {
//         $akumulasi = 0;
//         $investasi = 0;
//         $currentMonth = date('m'); // Mendapatkan bulan saat ini (2 digit)

//         foreach ($asets as $aset) {
//             if ($aset->wkt_ekonomis != 0) {
//                 $penyusutan = $aset->nilai / $aset->wkt_ekonomis * $aset->jumlah;
//             } else {
//                 $penyusutan = 0;
//             }

//             $saat_ini = ($aset->jumlah * $aset->nilai) - (masaPakai($aset->tgl_beli, $aset->wkt_ekonomis)['masa_pakai'] * $penyusutan);

//             // Jika bulan saat ini adalah antara Oktober dan Desember, set akumulasi menjadi 0
//             if ($currentMonth >= 1 && $currentMonth <= 4) {
//                 $akumulasi = 0;
//             } else {
//                 // Jika belum mencapai masa ekonomis penuh, tambahkan penyusutan ke akumulasi
//                 if (masaPakai($aset->created_at, $aset->wkt_ekonomis)['tahun'] < $aset->wkt_ekonomis) {
//                     $akumulasi += $penyusutan;
//                 }
//             }

//             $investasi += $saat_ini;
//         }

//         return ['inven' => $investasi, 'akumu' => $akumulasi];
//     }
// }

// if (!function_exists('akumulasiPenyusutanTahun')) {
//     function akumulasiPenyusutanTahun($asets)
//     {
//         $akumulasi = 0;
//         $investasi = 0;
//         $currentMonth = date('m'); // Mendapatkan bulan saat ini (format angka 2 digit)

//         foreach ($asets as $aset) {
//             if ($aset->wkt_ekonomis != 0) {
//                 $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
//             } else {
//                 $penyusutan = 0;
//             }

//             // Hitung nilai aset saat ini berdasarkan masa pakai dan penyusutan
//             $saat_ini = $aset->nilai - masaPakai($aset->created_at, $aset->wkt_ekonomis)['masa_pakai'] * $penyusutan;

//             // Periksa apakah bulan saat ini adalah antara Oktober hingga Desember
//             if ($currentMonth >= 1 && $currentMonth <= 4) {
//                 // Jika Oktober hingga Desember, set akumulasi penyusutan menjadi 0
//                 $akumulasi = 0;
//             } else {
//                 // Jika belum mencapai masa ekonomis penuh, tambahkan penyusutan ke akumulasi
//                 if (masaPakai($aset->created_at, $aset->wkt_ekonomis)['tahun'] < $aset->wkt_ekonomis) {
//                     $akumulasi += $penyusutan;
//                 }
//             }

//             // Tambahkan nilai saat ini ke investasi
//             $investasi += $saat_ini;
//         }

//         return ['inven' => $investasi, 'akumu' => $akumulasi];
//     }
// }


// if (!function_exists('akumulasiPenyusutanIventasiTahun')) {
//     function akumulasiPenyusutanIventasiTahun($asets)
//     {
//         $akumulasi = 0;
//         $investasi = 0;
//         $currentMonth = date('m'); // Mendapatkan bulan saat ini (2 digit)

//         foreach ($asets as $aset) {
//             if ($aset->wkt_ekonomis != 0) {
//                 $penyusutan = $aset->nilai / $aset->wkt_ekonomis * $aset->jumlah;
//             } else {
//                 $penyusutan = 0;
//             }

//             $saat_ini = ($aset->jumlah * $aset->nilai) - (masaPakai($aset->tgl_beli, $aset->wkt_ekonomis)['masa_pakai'] * $penyusutan);

//             // Jika bulan saat ini adalah antara Oktober dan Desember, set akumulasi menjadi 0
//             if ($currentMonth >= 1 && $currentMonth <= 4) {
//                 $akumulasi = 0;
//             } else {
//                 // Jika belum mencapai masa ekonomis penuh, tambahkan penyusutan ke akumulasi
//                 if (masaPakai($aset->created_at, $aset->wkt_ekonomis)['tahun'] < $aset->wkt_ekonomis) {
//                     $akumulasi += $penyusutan;
//                 }
//             }

//             $investasi += $saat_ini;
//         }

//         return ['inven' => $investasi, 'akumu' => $akumulasi];
//     }
// }
