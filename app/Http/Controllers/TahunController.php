<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TahunController extends Controller
{
    /**
     * Menyimpan tahun yang dipilih ke dalam session.
     */
    public function setYear(Request $request)
    {
        // Validasi input tahun
        $request->validate([
            'tahun' => 'required|integer|min:2000|max:' . date('Y') + 1, // Menyesuaikan validasi tahun
        ]);

        // Simpan tahun yang dipilih ke dalam session
        session(['selected_year' => $request->tahun]);

        // Redirect kembali ke halaman sebelumnya atau halaman lain yang diinginkan
        return redirect()->back()->with('success', 'Tahun berhasil disimpan.');
    }

    /**
     * Menggunakan tahun dari session atau tahun saat ini sebagai default.
     */
    public function index()
    {
        // Ambil tahun yang tersimpan di session, jika tidak ada maka gunakan tahun sekarang
        $selectedYear = session('selected_year', date('Y'));

        // Lakukan sesuatu dengan tahun yang dipilih, misalnya filter data berdasarkan tahun
        // Misalnya ambil data dari database berdasarkan tahun yang dipilih
        // $data = Model::whereYear('created_at', $selectedYear)->get();

        return view('your-view', compact('selectedYear'));
    }
}
