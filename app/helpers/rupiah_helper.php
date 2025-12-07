<?php

use App\Models\Bukbesar;

if (!function_exists('formatRupiah')) {
    function formatRupiah($angka)
    {
        // Pastikan $angka adalah angka
        $angka = is_numeric($angka) ? floatval($angka) : 0;

        // Format ke dalam format rupiah
        return 'Rp'  . number_format($angka, 0, ',', '.');
    }
}


if (!function_exists('formatNomor')) {
    function formatNomor($angka)
    {
        return number_format($angka, 0, ',', '.');
    }
}

function rendem()
{
    // Pecah string dan ambil huruf pertama tiap kata dengan array_map
    $initials = array_map(function ($word) {
        return strtolower($word[0]); // Ambil huruf pertama dan jadikan huruf kecil
    }, explode(" ", 'p'));

    // Gabungkan hasil menjadi satu string
    $initialsString = implode("", $initials);

    // Menggunakan waktu saat ini sebagai basis (dalam mikrodetik untuk meningkatkan keunikan)
    $time = microtime();

    // Hash waktu dengan md5 dan ambil 4 karakter pertama
    $randomNumber = substr(md5($time), 0, 4);

    // // Tambahkan nomor acak 4 digit di belakangnya
    // $randomNumber = rand(1000, 9999);

    return $initialsString . $randomNumber;
}
