<?php

use App\Models\Ekuit;

if (!function_exists('formatTanggal')) {
    function formatTanggal($tanggal)
    {
        return date('d_F_Y', strtotime($tanggal));
    }
}

if (!function_exists('tanggal')) {
    function tanggal($tanggal)
    {
        return date('Y-m-d', strtotime($tanggal));
    }
}

if (!function_exists('masaPakai')) {
    function masaPakai($tahun_beli, $masa_ekonomis)
    {
        // Tahun yang dipilih dari sesi atau tahun sekarang
        $currentYear = session('selected_year', date('Y'));
        $tahunBeli = date('Y', strtotime($tahun_beli));

        // Mendapatkan bulan sekarang
        $currentMonth = date('m');

        // Menghitung selisih tahun
        $selisih = $currentYear - $tahunBeli;
        $selisih_tahun = $currentYear - $tahunBeli;

        // dd($currentYear . $tahunBeli);


        // Logika hanya berlaku jika tahun yang dipilih adalah tahun sekarang
        if ($currentYear != date('Y') || $currentMonth > 4) {
            $selisih += 1;
        }


        // Jika selisih negatif (tahun beli di masa depan), set ke 0
        if ($selisih < 0) {
            $selisih = 0;
        }

        // Jika selisih lebih dari masa ekonomis, masa pakai = masa ekonomis
        if ($selisih > $masa_ekonomis) {
            $masa_pakai = $masa_ekonomis;
        } else {
            // Menentukan masa pakai berdasarkan selisih dan masa ekonomis
            $masa_pakai = min($selisih, $masa_ekonomis);
        }

        return ['masa_pakai' => $masa_pakai, 'tahun' => $selisih_tahun];
    }
}





if (!function_exists('masaPakaiTahun')) {
    function masaPakaiTahun($tahun_beli, $masa_ekonomis)
    {
        $currentYear = Ekuit::user()->get()->first()->tahun ?? date('Y');

        // dd($currentYear);

        $tahunBeli = date('Y', strtotime($tahun_beli));

        // Mendapatkan bulan sekarang
        $currentMonth = date('m');

        // Menghitung selisih tahun
        $selisih = $currentYear - $tahunBeli;
        $selisih_tahun = $currentYear - $tahunBeli;

        // Logika hanya berlaku jika tahun yang dipilih adalah tahun sekarang
        if ($currentYear != date('Y') || $currentMonth > 4) {
            $selisih += 1;
        }

        // Jika selisih negatif (tahun beli di masa depan), set ke 0
        if ($selisih < 0) {
            $selisih = 0;
        }

        // Jika selisih lebih dari masa ekonomis, masa pakai = masa ekonomis
        if ($selisih > $masa_ekonomis) {
            $masa_pakai = $masa_ekonomis;
        } else {
            // Menentukan masa pakai berdasarkan selisih dan masa ekonomis
            $masa_pakai = min($selisih, $masa_ekonomis);
        }

        return ['masa_pakai' => $masa_pakai, 'tahun' => $selisih_tahun];
    }
}



if (!function_exists('created_at')) {
    function created_at()
    {
        $date = new DateTime(); // Menggunakan tanggal saat ini

        // Mengatur tahun berdasarkan session atau default tahun saat ini
        $date->setDate(session('selected_year', date('Y')), $date->format('m'), $date->format('d'));

        // Mengembalikan dalam format timestamps
        return $date->format('Y-m-d H:i:s'); // Menghasilkan timestamps dari tanggal yang diubah
    }
}
