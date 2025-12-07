<?php

use App\Models\Buk;
use App\Models\Ekuit;

if (!function_exists('updateBukuUmum')) {
    function updateBukuUmum($akun, $id_akun, $nilai)
    {
        return
            Buk::where('akun', $akun)->where('id_akun', $id_akun)->update(['nilai' => $nilai]);
    }
}


if (!function_exists('akumulasiPenyusutan')) {
    function akumulasiPenyusutan($asets)
    {
        $akumulasi = 0;
        $investasi = 0;
        $currentMonth = date('n'); // Mendapatkan bulan saat ini (format angka 1-12)

        foreach ($asets as $aset) {
            if ($aset->wkt_ekonomis != 0) {
                $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
            } else {
                $penyusutan = 0;
            }

            // Hitung nilai aset saat ini berdasarkan masa pakai dan penyusutan
            $masa_pakai = masaPakai($aset->created_at, $aset->wkt_ekonomis)['masa_pakai'];
            $saat_ini = $aset->nilai - $masa_pakai * $penyusutan;

            // Periksa apakah bulan saat ini adalah antara Januari hingga April
            if ($currentMonth >= 1 && $currentMonth <= 4 && session('selected_year', date('Y'))  == date('Y')) {
                // Jika Januari hingga April, set akumulasi penyusutan menjadi 0
                $akumulasi = 0;
            } else {
                // Jika belum mencapai masa ekonomis penuh, tambahkan penyusutan ke akumulasi
                if (masaPakai($aset->created_at, $aset->wkt_ekonomis)['tahun'] < $aset->wkt_ekonomis) {
                    $akumulasi += $penyusutan;
                }
            }
            if ($aset->wkt_ekonomis == 0) {
                $saat_ini = $aset->nilai;
            } elseif ($aset->wkt_ekonomis != 0 && $masa_pakai == $aset->wkt_ekonomis) {
                $saat_ini = 0;
            }

            // Tambahkan nilai saat ini ke investasi
            $investasi += $saat_ini;
        }

        // dd($array_penyusutan);

        return ['inven' => $investasi, 'akumu' => $akumulasi];
    }
}



if (!function_exists('akumulasiPenyusutanIventasi')) {
    function akumulasiPenyusutanIventasi($asets)
    {
        $investasi = 0;
        $akumulasi = 0;

        $array_penyusutan = [];
        foreach ($asets as $aset) {
            $masa_pakai = masaPakai($aset->tgl_beli, $aset->wkt_ekonomis)['masa_pakai'];
            $tahun = masaPakai($aset->tgl_beli, $aset->wkt_ekonomis)['tahun'];

            $bulan_sekarang = date('n'); // Ambil bulan saat ini

            // dd($tahun);

            // Default nilai penyusutan
            $penyusutan = $tahun < ($aset->wkt_ekonomis) ? ($aset->jumlah * ($aset->nilai / $aset->wkt_ekonomis)) : 0;

            if ($bulan_sekarang >= 1 && $bulan_sekarang <= 4 && session('selected_year', date('Y'))  == date('Y')) {
                // Jika bulan Januari - April
                $penyusutan = 0;
                $ok = null;
                $saat_ini = $aset->nilai * $aset->jumlah - (($masa_pakai) * ($aset->jumlah * ($aset->nilai / $aset->wkt_ekonomis)));
            } else {
                // Jika bukan bulan Januari - April
                $saat_ini = $aset->nilai * $aset->jumlah - ($masa_pakai * $penyusutan);
                $ok = 'ok';
            }
            $array_penyusutan[] = [
                'penyusutan' => $penyusutan,
                'masa_pakai' => $masa_pakai - 1,
                'ekonimis' => $aset->wkt_ekonomis,
                'tahun' => $tahun,
                'saat_ini' => $saat_ini,
                'ok' => $ok,
                'Nilai' => $aset->nilai,
                'Jumlah' => $aset->jumlah
            ];

            if ($aset->wkt_ekonomis == 0) {
                $saat_ini = $aset->nilai;
            } elseif ($aset->wkt_ekonomis != 0 && $masa_pakai == $aset->wkt_ekonomis) {
                $saat_ini = 0;
            }



            $investasi += $saat_ini;
            $akumulasi += $penyusutan;
        }

        return ['inven' => $investasi, 'akumu' => $akumulasi];
    }
}


if (!function_exists('akumulasiPenyusutanTahun')) {
    function akumulasiPenyusutanTahun($asets)
    {
        $akumulasi = 0;
        $investasi = 0;
        $currentMonth = date('n'); // Mendapatkan bulan saat ini (format angka 1-12)
        $currentYear = Ekuit::user()->get()->first()->tahun ?? session('selected_year', date('Y'));

        foreach ($asets as $aset) {
            if ($aset->wkt_ekonomis != 0) {
                $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
            } else {
                $penyusutan = 0;
            }
            // Hitung nilai aset saat ini berdasarkan masa pakai dan penyusutan
            $masa_pakai = masaPakaiTahun($aset->created_at, $aset->wkt_ekonomis)['masa_pakai'];
            $saat_ini = $aset->nilai - $masa_pakai * $penyusutan;


            // Periksa apakah bulan saat ini adalah antara Januari hingga April
            if ($currentMonth >= 1 && $currentMonth <= 4 && $currentMonth  == date('Y')) {
                // Jika Januari hingga April, set akumulasi penyusutan menjadi 0
                $akumulasi = 0;
            } else {
                // Jika belum mencapai masa ekonomis penuh, tambahkan penyusutan ke akumulasi
                if (masaPakaiTahun($aset->created_at, $aset->wkt_ekonomis)['tahun'] < $aset->wkt_ekonomis) {
                    $akumulasi += $penyusutan;
                }
            }

            if ($aset->wkt_ekonomis == 0) {
                $saat_ini = $aset->nilai;
            } elseif ($aset->wkt_ekonomis != 0 && $masa_pakai == $aset->wkt_ekonomis) {
                $saat_ini = 0;
            }

            // Tambahkan nilai saat ini ke investasi
            $investasi += $saat_ini;
        }

        return ['inven' => $investasi, 'akumu' => $akumulasi];
    }
}



if (!function_exists('akumulasiPenyusutanIventasiTahun')) {
    function akumulasiPenyusutanIventasiTahun($asets)
    {
        $investasi = 0;
        $akumulasi = 0;
        $currentYear = Ekuit::user()->get()->first()->tahun ?? session('selected_year', date('Y'));

        $array_penyusutan = [];
        foreach ($asets as $aset) {
            $masa_pakai = masaPakaiTahun($aset->tgl_beli, $aset->wkt_ekonomis)['masa_pakai'];
            $tahun = masaPakaiTahun($aset->tgl_beli, $aset->wkt_ekonomis)['tahun'];

            $bulan_sekarang = date('n');


            // Default nilai penyusutan
            $penyusutan = $tahun < ($aset->wkt_ekonomis) ? ($aset->jumlah * ($aset->nilai / $aset->wkt_ekonomis)) : 0;

            if ($bulan_sekarang >= 1 && $bulan_sekarang <= 4 && $currentYear == date('Y')) {
                // Jika bulan Januari - April
                $penyusutan = 0;
                $ok = null;
                $saat_ini = $aset->nilai * $aset->jumlah - (($masa_pakai) * (($aset->jumlah * $aset->nilai) / $aset->wkt_ekonomis));
            } else {
                $ok = 'ok';
                // Jika bukan bulan Januari - April
                $saat_ini = $aset->nilai * $aset->jumlah - ($masa_pakai * $penyusutan);
            }



            if ($aset->wkt_ekonomis == 0) {
                $saat_ini = $aset->nilai;
            } elseif ($aset->wkt_ekonomis != 0 && $masa_pakai == $aset->wkt_ekonomis) {
                $saat_ini = 0;
            }

            $investasi += $saat_ini;

            $akumulasi += $penyusutan;

            $array_penyusutan[] = [
                'penyusutan' => $penyusutan,
                'masa_pakai' => $masa_pakai - 1,
                'ekonimis' => $aset->wkt_ekonomis,
                'Akum' => $akumulasi,
                'tahun' => $tahun,
                'saat_ini' => $saat_ini,
                'ok' => $ok,
                'Nilai' => $aset->nilai,
                'Jumlah' => $aset->jumlah,
                'sleck' => session('selected_year', date('Y'))
            ];
        }

        // dd($array_penyusutan);


        return ['inven' => $investasi, 'akumu' => $akumulasi];
    }
}
