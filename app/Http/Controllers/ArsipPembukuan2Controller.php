<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArsipPembukuan2Controller extends Controller
{
    // Sample dataset (replace with DB later)
    protected function sampleRows()
    {
        return [
            ['id'=>1, 'transaksi'=>'Setoran Modal',    'nomor'=>'A-001', 'jenis'=>'Bukti Kas Masuk',  'bukti'=>'KW-001.pdf'],
            ['id'=>2, 'transaksi'=>'Pembelian ATK',    'nomor'=>'B-012', 'jenis'=>'Bukti Kas Keluar', 'bukti'=>'INV-012.pdf'],
            ['id'=>3, 'transaksi'=>'Setoran Bank',     'nomor'=>'C-100', 'jenis'=>'Bukti Bank Masuk', 'bukti'=>'TRF-100.pdf'],
            ['id'=>4, 'transaksi'=>'Tarik Tunai',      'nomor'=>'D-055', 'jenis'=>'Bukti Kas Keluar', 'bukti'=>'KW-055.pdf'],
            ['id'=>5, 'transaksi'=>'Setoran Pelanggan', 'nomor'=>'E-200','jenis'=>'Bukti Bank Masuk', 'bukti'=>'TRF-200.pdf'],
        ];
    }

    public function index(Request $request)
    {
        $years = range(2024, 2028);               // contoh range tahun
        $selectedYear = $request->get('year', 2026);
        $types = ['Semua','Bukti Kas Masuk','Bukti Kas Keluar','Bukti Bank Masuk','Bukti Bank Keluar'];

        $rows = $this->sampleRows();

        // jika ingin ambil dari DB, paginasi, dsb. untuk sekarang kirim langsung sample
        return view('spj.arsip_pembukuan_2.index', compact('years','selectedYear','types','rows'));
    }

    /**
     * Rekap print page — filter server-side (simple array filter untuk demo)
     */
    public function rekap(Request $request)
    {
        $year = $request->get('year', 2026);
        $filter = $request->get('filter', 'Semua');

        $rows = $this->sampleRows();

        if ($filter && $filter !== 'Semua') {
            $rows = array_filter($rows, function($r) use ($filter) {
                return $r['jenis'] === $filter;
            });
            // reindex
            $rows = array_values($rows);
        }

        return view('spj.arsip_pembukuan_2.rekap', compact('rows','year','filter'));
    }
}
