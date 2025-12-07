<?php

use App\Models\Buk;
use App\Models\Unit;
use App\Models\Bdmuk;
use App\Models\Bangunan;
use App\Models\Investasi;
use App\Models\Aktivalain;
use Illuminate\Support\Facades\DB;

if (!function_exists('labaRugi')) {
    function labaRugi($tahun_sekarang)
    {

        $unit_usaha = Unit::user()->get();
        $array_pendapatan = [];
        $array_pendapatan_tahun = [];
        $jenis_lr_pu = [];
        $jenis_lr_bo = [];
        $jenis_lr_bno = [];

        // Loop untuk setiap unit usaha
        foreach ($unit_usaha as $unit) {
            $array_pendapatan['pu' . strtolower($unit->kode)] = [];
            $array_pendapatan_tahun['pu' . strtolower($unit->kode)] = 0;
            $jenis_lr_pu[] =  'pu' . strtolower($unit->kode);
        }
        // Loop untuk setiap unit usaha
        foreach ($unit_usaha as $unit) {
            $array_pendapatan['bo' . strtolower($unit->kode)] = [];
            $array_pendapatan_tahun['bo' . strtolower($unit->kode)] = 0;
            $jenis_lr_bo[] =  'bo' . strtolower($unit->kode);
        }

        // Tambahan untuk 'bno' keys secara manual
        for ($i = 1; $i <= 5; $i++) {
            $array_pendapatan['bno' . $i] = [];
            $array_pendapatan_tahun['bno' . $i] = 0;
            $jenis_lr_bno[] = 'bno' . $i;
        }


        // Inisialisasi array untuk setiap jenis_lr
        $pendapatan = $array_pendapatan;



        // Inisialisasi variabel untuk akumulasi tahunan
        $totalPendapatanTahun = $array_pendapatan_tahun;


        // Loop untuk setiap bulan (1 - 12)
        for ($i = 1; $i <= 12; $i++) {
            // Loop untuk setiap jenis_lr
            foreach (array_keys($pendapatan) as $jenis_lr) {
                // Ambil data berdasarkan bulan, tahun, dan jenis_lr
                $buku_kas = Buk::user()->where('user_id', auth()->id()) // Filter berdasarkan user
                    ->user()->whereYear('tanggal', $tahun_sekarang) // Filter berdasarkan tahun
                    ->whereMonth('tanggal', $i) // Filter berdasarkan bulan
                    ->where('jenis_lr', $jenis_lr) // Filter berdasarkan jenis_lr
                    ->get();

                // Variabel untuk menampung total nilai per bulan
                $totalNilai = 0;

                // Loop untuk setiap transaksi dalam bulan tersebut
                foreach ($buku_kas as $transaksi) {
                    if ($transaksi->jenis == 'debit') {
                        // Jika debit, tambahkan nilainya
                        $totalNilai += $transaksi->nilai;
                    } elseif ($transaksi->jenis == 'kredit' and in_array($jenis_lr, $jenis_lr_pu)) {
                        // Jika kredit, kurangi nilainya
                        $totalNilai -= $transaksi->nilai;
                    } else {
                        $totalNilai += $transaksi->nilai;
                    }
                }

                // Simpan total pendapatan per bulan untuk jenis_lr tertentu
                $pendapatan[$jenis_lr][$i] = $totalNilai;

                // Akumulasi total tahunan untuk jenis_lr tersebut
                $totalPendapatanTahun[$jenis_lr] += $totalNilai;
            }
        }


        // Array untuk menyimpan hasil per bulan dan per jenis lr
        $pendapatanBulan = [
            'pu' => array_fill(1, 12, 0),
            'bo' => array_fill(1, 12, 0),
            'bno' => array_fill(1, 12, 0),
        ];

        // Total pendapatanBulan tahunan
        $tahun = [
            'pu' => 0,
            'bo' => 0,
            'bno' => 0,
        ];

        // Mengambil semua transaksi dalam tahun yang dipilih
        $transaksis = Buk::user()->whereYear('tanggal', $tahun_sekarang)->get();


        foreach ($transaksis as $transaksi) {
            // Mengonversi 'tanggal' menjadi objek Carbon jika diperlukan
            $tanggal = \Carbon\Carbon::parse($transaksi->tanggal); // Konversi ke Carbon jika belum berbentuk tanggal
            $month = $tanggal->month; // Mendapatkan bulan dari kolom tanggal
            $jenis_lr = strtolower($transaksi->jenis_lr); // Mengkonversi ke lowercase untuk kemudahan
            $nilai = $transaksi->nilai;

            // Jika jenis_lr adalah kredit, nilai akan negatif
            if ($transaksi->jenis == 'kredit' && in_array($jenis_lr, $jenis_lr_pu)) {
                $nilai = -$nilai;
            }

            // Menambah nilai transaksi ke bulan dan jenis_lr yang sesuai
            if (in_array($jenis_lr, $jenis_lr_pu)) {
                $pendapatanBulan['pu'][$month] += $nilai;
                $tahun['pu'] += $nilai;
            } elseif (in_array($jenis_lr, $jenis_lr_bo)) {
                $pendapatanBulan['bo'][$month] += $nilai;
                $tahun['bo'] += $nilai;
            } elseif (in_array($jenis_lr, $jenis_lr_bno)) {
                $pendapatanBulan['bno'][$month] += $nilai;
                $tahun['bno'] += $nilai;
            }
        }




        $akumulasi = 0;

        $iventaris = Investasi::user()->get();
        $iventari = akumulasiPenyusutanIventasi($iventaris)['akumu'];


        $bangunans = Bangunan::user()->get();
        $bangunan = akumulasiPenyusutan($bangunans)['akumu'];

        $bdmuks = Bdmuk::user()->get();
        $bdmuk = akumulasiPenyusutan($bdmuks)['akumu'];

        $aktiva_lain = Aktivalain::user()->get();
        $aktiva = akumulasiPenyusutan($aktiva_lain)['akumu'];

        $akumulasi = $iventari + $bangunan + $bdmuk + $aktiva;

        // dd('iventari = ' . $iventari  . ' Bangunan = ' . $bangunan . ' Bdmuk = ' . $bdmuk . ' aktiva = ' . $aktiva);

        $totalBiaya = [];
        $labaRugi = [];


        foreach ($pendapatanBulan['bo'] as $key => $value) {
            $totalBiaya[] = $pendapatanBulan['bno'][$key] + $value;
            $labaRugi[] = $pendapatanBulan['pu'][$key] - ($pendapatanBulan['bno'][$key] + $value);
        }

        $akumulasitotalBiaya = array_sum($pendapatanBulan['bo']) + array_sum($pendapatanBulan['bno']);
        $akumulasilabaRugi = array_sum($pendapatanBulan['pu']) - (array_sum($pendapatanBulan['bo']) + array_sum($pendapatanBulan['bno']) + $akumulasi);


        $akumulasiBiaya = $akumulasitotalBiaya + $akumulasi;
        $totalLabaRugi = $akumulasilabaRugi;


        // dd([
        //     'pendapatan' => $pendapatan,
        //     'pendapatanBulan' => $pendapatanBulan,
        //     'pendapatanTahun' => $tahun,
        //     'tahun' => $totalPendapatanTahun,
        //     'totalBiaya' => $totalBiaya,
        //     'akumulasiBiaya' => $akumulasiBiaya,
        //     'labaRugi' => $labaRugi,
        //     'totalLabaRugi' => $totalLabaRugi,
        //     'akumulasi_penyusutan' => $akumulasi
        // ]);
        return ([
            'pendapatan' => $pendapatan,
            'pendapatanBulan' => $pendapatanBulan,
            'pendapatanTahun' => $tahun,
            'tahun' => $totalPendapatanTahun,
            'totalBiaya' => $totalBiaya,
            'akumulasiBiaya' => $akumulasiBiaya,
            'labaRugi' => $labaRugi,
            'totalLabaRugi' => $totalLabaRugi,
            'akumulasi_penyusutan' => $akumulasi
        ]);
    }
}

if (!function_exists('labaRugiTahun')) {
    function labaRugiTahun($tahun_sekarang)
    {

        $tahun_sekarang = strval($tahun_sekarang);


        $unit_usaha = Unit::user()->get();
        $array_pendapatan = [];
        $array_pendapatan_tahun = [];
        $jenis_lr_pu = [];
        $jenis_lr_bo = [];
        $jenis_lr_bno = [];

        // Loop untuk setiap unit usaha
        foreach ($unit_usaha as $unit) {
            $array_pendapatan['pu' . strtolower($unit->kode)] = [];
            $array_pendapatan_tahun['pu' . strtolower($unit->kode)] = 0;
            $jenis_lr_pu[] =  'pu' . strtolower($unit->kode);
        }
        // Loop untuk setiap unit usaha
        foreach ($unit_usaha as $unit) {
            $array_pendapatan['bo' . strtolower($unit->kode)] = [];
            $array_pendapatan_tahun['bo' . strtolower($unit->kode)] = 0;
            $jenis_lr_bo[] =  'bo' . strtolower($unit->kode);
        }

        // Tambahan untuk 'bno' keys secara manual
        for ($i = 1; $i <= 5; $i++) {
            $array_pendapatan['bno' . $i] = [];
            $array_pendapatan_tahun['bno' . $i] = 0;
            $jenis_lr_bno[] = 'bno' . $i;
        }


        // Inisialisasi array untuk setiap jenis_lr
        $pendapatan = $array_pendapatan;



        // Inisialisasi variabel untuk akumulasi tahunan
        $totalPendapatanTahun = $array_pendapatan_tahun;

        $userId = auth()->id(); // Mendapatkan ID user yang sedang login

        // Loop untuk setiap bulan (1 - 12)
        for ($i = 1; $i <= 12; $i++) {
            // Loop untuk setiap jenis_lr
            foreach (array_keys($pendapatan) as $jenis_lr) {
                // Ambil data berdasarkan bulan, tahun, dan jenis_lr
                $buku_kas
                    = DB::table('buks')
                    ->where('user_id', $userId) // Sesuaikan dengan nama kolom user_id pada tabel Anda
                    ->whereYear('tanggal', $tahun_sekarang)
                    ->whereMonth('tanggal', $i) // Filter berdasarkan bulan
                    ->where('jenis_lr', $jenis_lr) // Filter berdasarkan jenis_lr
                    ->get();

                // Variabel untuk menampung total nilai per bulan
                $totalNilai = 0;

                // Loop untuk setiap transaksi dalam bulan tersebut
                foreach ($buku_kas as $transaksi) {
                    if ($transaksi->jenis == 'debit') {
                        // Jika debit, tambahkan nilainya
                        $totalNilai += $transaksi->nilai;
                    } elseif ($transaksi->jenis == 'kredit' and in_array($jenis_lr, $jenis_lr_pu)) {
                        // Jika kredit, kurangi nilainya
                        $totalNilai -= $transaksi->nilai;
                    } else {
                        $totalNilai += $transaksi->nilai;
                    }
                }

                // Simpan total pendapatan per bulan untuk jenis_lr tertentu
                $pendapatan[$jenis_lr][$i] = $totalNilai;

                // Akumulasi total tahunan untuk jenis_lr tersebut
                $totalPendapatanTahun[$jenis_lr] += $totalNilai;
            }
        }


        // Array untuk menyimpan hasil per bulan dan per jenis lr
        $pendapatanBulan = [
            'pu' => array_fill(1, 12, 0),
            'bo' => array_fill(1, 12, 0),
            'bno' => array_fill(1, 12, 0),
        ];

        // Total pendapatanBulan tahunan
        $tahun = [
            'pu' => 0,
            'bo' => 0,
            'bno' => 0,
        ];



        $transaksis = DB::table('buks')
            ->where('user_id', $userId) // Sesuaikan dengan nama kolom user_id pada tabel Anda
            ->whereYear('tanggal', $tahun_sekarang)
            ->get();


        foreach ($transaksis as $transaksi) {
            // Mengonversi 'tanggal' menjadi objek Carbon jika diperlukan
            $tanggal = \Carbon\Carbon::parse($transaksi->tanggal); // Konversi ke Carbon jika belum berbentuk tanggal
            $month = $tanggal->month; // Mendapatkan bulan dari kolom tanggal
            $jenis_lr = strtolower($transaksi->jenis_lr); // Mengkonversi ke lowercase untuk kemudahan
            $nilai = $transaksi->nilai;

            // Jika jenis_lr adalah kredit, nilai akan negatif
            if ($transaksi->jenis == 'kredit' && in_array($jenis_lr, $jenis_lr_pu)) {
                $nilai = -$nilai;
            }

            // Menambah nilai transaksi ke bulan dan jenis_lr yang sesuai
            if (in_array($jenis_lr, $jenis_lr_pu)) {
                $pendapatanBulan['pu'][$month] += $nilai;
                $tahun['pu'] += $nilai;
            } elseif (in_array($jenis_lr, $jenis_lr_bo)) {
                $pendapatanBulan['bo'][$month] += $nilai;
                $tahun['bo'] += $nilai;
            } elseif (in_array($jenis_lr, $jenis_lr_bno)) {
                $pendapatanBulan['bno'][$month] += $nilai;
                $tahun['bno'] += $nilai;
            }
        }




        $akumulasi = 0;

        // $tahun_sekarang = intval($tahun_sekarang); // Ensure it's an integer

        // Ambil data investasi
        $iventaris = DB::table('investasis')
            ->where('user_id', auth()->id())
            ->whereYear('tgl_beli', '<=', $tahun_sekarang)
            ->get();


        $iventari = akumulasiPenyusutanIventasiTahun($iventaris)['akumu'];



        // Ambil data bangunan
        $bangunans = DB::table('bangunans')
            ->where('user_id', auth()->id())
            ->whereYear('created_at', '<=', $tahun_sekarang)
            ->get();
        $bangunan = akumulasiPenyusutanTahun($bangunans)['akumu'];

        // Ambil data BDMUK
        $bdmuks = DB::table('bdmuks')
            ->where('user_id', auth()->id())
            ->whereYear('created_at', '<=', $tahun_sekarang)
            ->get();
        $bdmuk = akumulasiPenyusutanTahun($bdmuks)['akumu'];

        // Ambil data aktiva lain
        $aktiva_lain = DB::table('aktivalains')
            ->where('user_id', auth()->id())
            ->whereYear('created_at', '<=', $tahun_sekarang)
            ->get();
        $aktiva = akumulasiPenyusutanTahun($aktiva_lain)['akumu'];

        $akumulasi = $iventari + $bangunan + $bdmuk + $aktiva;

        // dd('iventari = ' . $iventari  . ' Bangunan = ' . $bangunan . ' Bdmuk = ' . $bdmuk . ' aktiva = ' . $aktiva);


        $totalBiaya = [];
        $labaRugi = [];


        foreach ($pendapatanBulan['bo'] as $key => $value) {
            $totalBiaya[] = $pendapatanBulan['bno'][$key] + $value;
            $labaRugi[] = $pendapatanBulan['pu'][$key] - ($pendapatanBulan['bno'][$key] + $value);
        }

        $akumulasitotalBiaya = array_sum($pendapatanBulan['bo']) + array_sum($pendapatanBulan['bno']);
        $akumulasilabaRugi = array_sum($pendapatanBulan['pu']) - (array_sum($pendapatanBulan['bo']) + array_sum($pendapatanBulan['bno']) + $akumulasi);


        $akumulasiBiaya = $akumulasitotalBiaya + $akumulasi;
        $totalLabaRugi = $akumulasilabaRugi;

        // dd([
        //     'pendapatan' => $pendapatan,
        //     'pendapatanBulan' => $pendapatanBulan,
        //     'pendapatanTahun' => $tahun,
        //     'tahun' => $totalPendapatanTahun,
        //     'totalBiaya' => $totalBiaya,
        //     'akumulasiBiaya' => $akumulasiBiaya,
        //     'labaRugi' => $labaRugi,
        //     'totalLabaRugi' => $totalLabaRugi,
        //     'akumulasi_penyusutan' => $akumulasi
        // ]);

        return ([
            'pendapatan' => $pendapatan,
            'pendapatanBulan' => $pendapatanBulan,
            'pendapatanTahun' => $tahun,
            'tahun' => $totalPendapatanTahun,
            'totalBiaya' => $totalBiaya,
            'akumulasiBiaya' => $akumulasiBiaya,
            'labaRugi' => $labaRugi,
            'totalLabaRugi' => $totalLabaRugi,
            'akumulasi_penyusutan' => $akumulasi
        ]);
    }
}
