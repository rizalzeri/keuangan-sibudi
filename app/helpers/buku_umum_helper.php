<?php

use App\Models\Buk;
use Illuminate\Support\Facades\DB;

if (!function_exists('bukuUmum')) {
    function bukuUmum($transaksi, $jenis, $jenis_lr, $jenis_dana, $nilai,  $akun, $id_akun, $tanggal)
    {
        $user_id = auth()->user()->id;

        return Buk::create(['transaksi' => $transaksi, 'jenis' => $jenis, 'jenis_lr' => $jenis_lr, 'jenis_dana' => $jenis_dana, 'nilai' => $nilai, 'user_id' => $user_id, 'akun' => $akun, 'id_akun' => $id_akun, 'tanggal' => $tanggal]);
    }
}

if (!function_exists('histori')) {
    function histori($id, $table, $data, $aksi, $id_table = null) // Set nilai default null
    {
        $sessionData = session('histori', []);

        $sessionData[] = [
            'id' => $id,
            'table' => $table,
            'data' => $data,
            'aksi' => $aksi,
            'id_table' => $id_table // Akan bernilai null jika tidak diisi
        ];

        session(['histori' => $sessionData]);
    }
}

if (!function_exists('undo')) {
    function undo()
    {
        // Ambil data array dari session
        $sessionData = session('histori', []);


        // Pastikan array tidak kosong sebelum mengambil elemen terakhir
        if (!empty($sessionData)) {
            // Ambil elemen terakhir dari array
            $lastData = end($sessionData);

            // Ambil ID dari elemen terakhir
            $lastId = $lastData['id'] ?? null;

            // Gunakan $lastId sesuai kebutuhan
        } else {
            // Jika array kosong
            $lastId = null;
        }
        // Nama id yang ingin dihapus
        $idToRemove = $lastId; // Ganti $id dengan nilai ID yang sesuai

        // Cari elemen yang memiliki nilai `id` sesuai dan hapus
        foreach ($sessionData as $index => $data) {
            if (isset($data['id']) && $data['id'] === $idToRemove) {
                if ($data['aksi'] == 'create') {
                    DB::table($data['table'])->where('id', $data['id_table'])->delete();
                } elseif ($data['aksi'] == 'update') {
                    DB::table($data['table'])->where('id', $data['id_table'])->update($data['data']);
                } elseif ($data['aksi'] == 'delete')
                    DB::table($data['table'])->insert($data['data']);
                unset($sessionData[$index]);
            }
        }

        // Menghapus elemen terakhir dari array jika array tidak kosong
        // if (!empty($sessionData)) {
        //     array_pop($sessionData); // Menghapus elemen terakhir
        // }

        // Reindex array untuk menghilangkan gap dalam nomor indeks
        $sessionData = array_values($sessionData);

        // Simpan kembali array yang telah diperbarui ke session
        session(['histori' => $sessionData]);
    }
}
