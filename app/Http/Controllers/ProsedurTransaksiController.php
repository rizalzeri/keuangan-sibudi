<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProsedurTransaksiController extends Controller
{
    public function bukti_kas_masuk()
    {
        // nanti isi logic ambil data & return view
        return view('spj.prosedur_transaksi.bukti_kas_masuk.index');
    }
    public function store_kas_masuk(Request $request)
    {
        // contoh: validasi sederhana
        $request->validate([
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string',
            'nominal' => 'nullable|numeric',
        ]);

        // TODO: Simpan ke DB. Untuk sekarang kita hanya mengembalikan success.
        // Jika ingin menyimpan nanti, lakukan Model::create([...]) di sini.

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan.',
            'data' => $request->only(['tanggal','nama_transaksi','sumber','nominal','penerima','menyetujui','mengetahui'])
        ]);
    }

    /**
     * Halaman print yang menerima data via query string.
     */
    public function print_kas_masuk(Request $request)
    {
        // Ambil semua query params untuk ditampilkan di view print
        $data = $request->all();

        return view('spj.prosedur_transaksi.bukti_kas_masuk.cetak', compact('data'));
    }

    // kas keluar
    public function bukti_kas_keluar()
    {
        // nanti isi logic ambil data & return view
        return view('spj.prosedur_transaksi.bukti_kas_keluar.index');
    }
    public function store_kas_keluar(Request $request)
    {
        // contoh: validasi sederhana
        $request->validate([
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string',
            'nominal' => 'nullable|numeric',
        ]);

        // TODO: Simpan ke DB. Untuk sekarang kita hanya mengembalikan success.
        // Jika ingin menyimpan nanti, lakukan Model::create([...]) di sini.

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan.',
            'data' => $request->only(['tanggal','nama_transaksi','sumber','nominal','penerima','menyetujui','mengetahui'])
        ]);
    }

    /**
     * Halaman print yang menerima data via query string.
     */
    public function print_kas_keluar(Request $request)
    {
        // Ambil semua query params untuk ditampilkan di view print
        $data = $request->all();

        return view('spj.prosedur_transaksi.bukti_kas_keluar.cetak', compact('data'));
    }

    // bank keluar
    public function bukti_bank_keluar()
    {
        // nanti isi logic ambil data & return view
        return view('spj.prosedur_transaksi.bukti_bank_keluar.index');
    }
    public function store_bank_keluar(Request $request)
    {
        // contoh: validasi sederhana
        $request->validate([
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string',
            'nominal' => 'nullable|numeric',
        ]);

        // TODO: Simpan ke DB. Untuk sekarang kita hanya mengembalikan success.
        // Jika ingin menyimpan nanti, lakukan Model::create([...]) di sini.

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan.',
            'data' => $request->only(['tanggal','nama_transaksi','sumber','nominal','penerima','menyetujui','mengetahui'])
        ]);
    }

    /**
     * Halaman print yang menerima data via query string.
     */
    public function print_bank_keluar(Request $request)
    {
        // Ambil semua query params untuk ditampilkan di view print
        $data = $request->all();

        return view('spj.prosedur_transaksi.bukti_bank_keluar.cetak', compact('data'));
    }

    // bank masuk
    public function bukti_bank_masuk()
    {
        // nanti isi logic ambil data & return view
        return view('spj.prosedur_transaksi.bukti_bank_masuk.index');
    }
    public function store_bank_masuk(Request $request)
    {
        // contoh: validasi sederhana
        $request->validate([
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string',
            'nominal' => 'nullable|numeric',
        ]);

        // TODO: Simpan ke DB. Untuk sekarang kita hanya mengembalikan success.
        // Jika ingin menyimpan nanti, lakukan Model::create([...]) di sini.

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan.',
            'data' => $request->only(['tanggal','nama_transaksi','sumber','nominal','penerima','menyetujui','mengetahui'])
        ]);
    }

    /**
     * Halaman print yang menerima data via query string.
     */
    public function print_bank_masuk(Request $request)
    {
        // Ambil semua query params untuk ditampilkan di view print
        $data = $request->all();

        return view('spj.prosedur_transaksi.bukti_bank_masuk.cetak', compact('data'));
    }
}
