<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipKasMasuk;
use App\Models\ArsipKasKeluar;
use App\Models\ArsipBankMasuk;
use App\Models\ArsipBankKeluar;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ArsipPembukuan2Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // tahun dipilih (default tahun sekarang)
        $selectedYear = $request->query('year', date('Y'));

        // jenis filter (Semua atau spesifik)
        $types = [
            'Semua',
            'Bukti Bank Masuk',
            'Bukti Bank Keluar',
            'Bukti Kas Masuk',
            'Bukti Kas Keluar',
        ];
        $selectedType = $request->query('type', 'Semua');

        $userId = auth()->id();

        // ambil list tahun yang ada di ke-4 tabel (unik) hanya milik user
        $years = collect([
            DB::table('arsip_bank_masuk')->where('users_id', $userId)->selectRaw('YEAR(tanggal_transaksi) as y')->pluck('y')->toArray(),
            DB::table('arsip_bank_keluar')->where('users_id', $userId)->selectRaw('YEAR(tanggal_transaksi) as y')->pluck('y')->toArray(),
            DB::table('arsip_kas_masuk')->where('users_id', $userId)->selectRaw('YEAR(tanggal_transaksi) as y')->pluck('y')->toArray(),
            DB::table('arsip_kas_keluar')->where('users_id', $userId)->selectRaw('YEAR(tanggal_transaksi) as y')->pluck('y')->toArray(),
        ])->flatten()->unique()->filter()->sortDesc()->values()->all();

        // helper mapping untuk tiap model -> jenis & kode singkatan
        $sources = [
            ['model' => ArsipBankMasuk::class, 'jenis' => 'Bukti Bank Masuk', 'abbr' => 'BBM'],
            ['model' => ArsipBankKeluar::class, 'jenis' => 'Bukti Bank Keluar', 'abbr' => 'BBK'],
            ['model' => ArsipKasMasuk::class, 'jenis' => 'Bukti Kas Masuk', 'abbr' => 'BKM'],
            ['model' => ArsipKasKeluar::class, 'jenis' => 'Bukti Kas Keluar', 'abbr' => 'BKK'],
        ];

        $rows = [];

        foreach ($sources as $s) {
            $model = $s['model'];
            $jenis = $s['jenis'];

            // jika user memfilter jenis: skip jika tidak sesuai
            if ($selectedType !== 'Semua' && $selectedType !== $jenis) {
                continue;
            }

            // ambil data untuk tahun yang dipilih, kategori_pembukuan = 2 (Pembukuan 2) — hanya milik user
            // URUTKAN BERDASARKAN created_at DESC agar record terbaru menurut waktu pembuatan muncul dulu
            $collection = $model::where('users_id', $userId)
                ->whereYear('tanggal_transaksi', $selectedYear)
                ->where('kategori_pembukuan', '2')
                ->orderBy('created_at', 'desc')   // <-- gunakan created_at desc
                ->get();

            foreach ($collection as $rec) {

                // format bukti dukung: dokumen_pendukung adalah JSON array (atau null)
                $dokArr = $rec->dokumen_pendukung ?? null;
                $buktiDukung = $this->formatDokumenPendukung($dokArr);

                $rows[] = [
                    'id' => $rec->id,
                    'transaksi' => $rec->nama_transaksi,
                    'nomor' => $rec->nomor_dokumen,
                    'tanggal' => $rec->tanggal_transaksi ? $rec->tanggal_transaksi->format('Y-m-d') : null,
                    'tanggal_display' => $rec->tanggal_transaksi ? $rec->tanggal_transaksi->format('d-m-Y') : null,
                    'jenis' => $jenis,
                    'bukti' => $buktiDukung,
                    'link_drive' => $rec->link_gdrive ?? null,
                    // simpan created_at sebagai string 'Y-m-d H:i:s' untuk sorting lintas-tabel
                    'created_at' => $rec->created_at ? $rec->created_at->format('Y-m-d H:i:s') : null,
                ];
            }
        }

        // Karena data berasal dari 4 tabel terpisah, pastikan urutan global $rows
        // berdasarkan created_at descending (terbaru di index 0)
        usort($rows, function ($a, $b) {
            $aTs = $a['created_at'] ? strtotime($a['created_at']) : 0;
            $bTs = $b['created_at'] ? strtotime($b['created_at']) : 0;
            return $bTs <=> $aTs;
        });

        // Jika tidak ada years hasilnya, sediakan default (tahun sekarang)
        if (empty($years)) {
            $years = [date('Y')];
        }
        // kirim ke view
        return view('spj.arsip_pembukuan_2.index', [
            'rows' => $rows,
            'years' => $years,
            'selectedYear' => (int) $selectedYear,
            'types' => $types,
            'selectedType' => $selectedType,
        ]);
    }

    private function monthToRoman(int $m): string
    {
        $map = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'];
        return $map[$m] ?? (string)$m;
    }

    private function formatDokumenPendukung($dokArr): string
    {
        if (is_null($dokArr)) return '-';
        // if JSON string given, try decode
        if (!is_array($dokArr)) {
            $try = json_decode($dokArr, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($try)) $dokArr = $try;
            else $dokArr = array_values(array_filter(array_map('trim', explode(',', (string)$dokArr))));
        }
        // prettify: "kwitansi" -> "Kwitansi"
        $pretty = array_map(function($v){
            $v = str_replace('_', ' ', (string)$v);
            return mb_convert_case($v, MB_CASE_TITLE, "UTF-8");
        }, $dokArr);

        return implode(', ', $pretty) ?: '-';
    }

    public function print_rekap(Request $request)
    {
        $selectedYear = $request->query('year', date('Y'));
        $selectedType = $request->query('type', 'Semua');
        $userId = auth()->id();

        $sources = [
            ['model' => ArsipBankMasuk::class, 'jenis' => 'Bukti Bank Masuk', 'abbr' => 'BBM'],
            ['model' => ArsipBankKeluar::class, 'jenis' => 'Bukti Bank Keluar', 'abbr' => 'BBK'],
            ['model' => ArsipKasMasuk::class, 'jenis' => 'Bukti Kas Masuk', 'abbr' => 'BKM'],
            ['model' => ArsipKasKeluar::class, 'jenis' => 'Bukti Kas Keluar', 'abbr' => 'BKK'],
        ];

        $rows = [];
        foreach ($sources as $s) {
            $model = $s['model'];
            $jenis = $s['jenis'];
            $abbr  = $s['abbr'];

            if ($selectedType !== 'Semua' && $selectedType !== $jenis) {
                continue;
            }

            // ambil data dan urutkan berdasarkan created_at desc
            $collection = $model::where('users_id', $userId)
                ->whereYear('tanggal_transaksi', $selectedYear)
                ->where('kategori_pembukuan', '2')
                ->orderBy('created_at', 'desc')   // <-- gunakan created_at desc
                ->get();

            foreach ($collection as $rec) {
                // dokumen pendukung -> format string (Kwitansi, Nota)
                $dokArr = $rec->dokumen_pendukung ?? null;
                $buktiDukung = $this->formatDokumenPendukung($dokArr);

                $rows[] = [
                    'id' => $rec->id,
                    'transaksi' => $rec->nama_transaksi,
                    'nomor' => $rec->nomor_dokumen,
                    'tanggal' => $rec->tanggal_transaksi ? $rec->tanggal_transaksi->format('d-m-Y') : '',
                    'jenis' => $jenis,
                    'bukti' => $buktiDukung,
                    'tautan' => $rec->link_gdrive,
                    // simpan created_at untuk urutan global
                    'created_at' => $rec->created_at ? $rec->created_at->format('Y-m-d H:i:s') : null,
                ];
            }
        }

        // pastikan rekap juga diurutkan secara global berdasarkan created_at desc
        usort($rows, function ($a, $b) {
            $aTs = $a['created_at'] ? strtotime($a['created_at']) : 0;
            $bTs = $b['created_at'] ? strtotime($b['created_at']) : 0;
            return $bTs <=> $aTs;
        });

        $data = [
            'rows' => $rows,
            'selectedYear' => $selectedYear,
            'selectedType' => $selectedType,
            'title' => 'Rekapitulasi Pembukuan SPJ Pembukuan 2'
        ];

        if (class_exists(PDF::class)) {
            $pdf = PDF::loadView('spj.arsip_pembukuan_2.rekap_print', $data)
                    ->setPaper('a4', 'portrait');

            return $pdf->stream('rekap_pembukuan_'.$selectedYear.'.pdf');
        }

        return view('spj.arsip_pembukuan_2.rekap_print', $data);
    }

    public function delete(Request $request, $id)
    {
        $jenis = $request->input('jenis');

        $modelMap = [
            'Bukti Bank Masuk' => \App\Models\ArsipBankMasuk::class,
            'Bukti Bank Keluar' => \App\Models\ArsipBankKeluar::class,
            'Bukti Kas Masuk' => \App\Models\ArsipKasMasuk::class,
            'Bukti Kas Keluar' => \App\Models\ArsipKasKeluar::class,
        ];

        if(!isset($modelMap[$jenis])){
            return response()->json(['success'=>false, 'message'=>'Jenis SPJ tidak valid']);
        }

        $model = $modelMap[$jenis];

        // hanya boleh hapus jika owner (users_id == auth()->id())
        $record = $model::where('id', $id)->where('users_id', auth()->id())->first();
        if(!$record){
            return response()->json(['success'=>false, 'message'=>'Data tidak ditemukan atau bukan milik Anda']);
        }

        try {
            $record->delete();
            return response()->json(['success'=>true, 'message'=>'Data berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success'=>false, 'message'=>'Gagal menghapus data: '.$e->getMessage()]);
        }
    }

}
