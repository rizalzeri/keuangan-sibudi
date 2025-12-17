<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipSuratKeluar;
use Carbon\Carbon;

class ArsipSuratKeluarController extends Controller
{
    public function index()
    {
        $surats = ArsipSuratKeluar::orderBy('id', 'asc')->get();
        return view('spj.arsip_surat_keluar.index', compact('surats'));
    }

    public function store(Request $request)
    {
        $rules = [
            // nomor_dokumen tidak wajib di input create
            'tujuan'        => 'nullable|string|max:255',
            'judul_surat'   => 'required|string|max:255',
            'isi'           => 'nullable|string',
            'link_gdrive'   => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        // generate nomor otomatis berdasarkan tahun saat ini
        $now = Carbon::now();
        $year = $now->year;
        $month = $now->month;
        // hitung jumlah surat pada tahun yang sama -> nomor = count + 1
        $countThisYear = ArsipSuratKeluar::whereYear('created_at', $year)->count();
        $no = $countThisYear + 1;
        $monthRoman = $this->monthToRoman($month);
        $nomorDokumen = "TU/{$no}/{$monthRoman}/{$year}";

        $data = array_merge($validated, ['nomor_dokumen' => $nomorDokumen]);

        $surat = ArsipSuratKeluar::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Surat keluar berhasil ditambahkan!',
                'data'    => $surat
            ], 201);
        }

        return back()->with('success', 'Surat keluar berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            // nomor_dokumen optional saat update (jika ingin dipertahankan gunakan null)
            'nomor_dokumen' => 'nullable|string|max:255',
            'tujuan'        => 'nullable|string|max:255',
            'judul_surat'   => 'required|string|max:255',
            'isi'           => 'nullable|string',
            'link_gdrive'   => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $surat = ArsipSuratKeluar::find($id);
        if (!$surat) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => "Surat dengan ID $id tidak ditemukan"
                ], 404);
            }
            return back()->with('error', "Surat dengan ID $id tidak ditemukan");
        }

        // Jika nomor_dokumen tidak dikirim (null atau kosong) -> jangan overwrite
        if (empty($validated['nomor_dokumen'])) {
            unset($validated['nomor_dokumen']);
        }

        $surat->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Surat keluar berhasil diperbarui!',
                'data'    => $surat
            ]);
        }

        return back()->with('success', 'Surat keluar berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $surat = ArsipSuratKeluar::find($id);
        if (!$surat) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => "Surat dengan ID $id tidak ditemukan"
                ], 404);
            }
            return back()->with('error', "Surat dengan ID $id tidak ditemukan");
        }

        $surat->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Surat keluar berhasil dihapus!'
            ]);
        }

        return back()->with('success', 'Surat keluar berhasil dihapus!');
    }

    private function monthToRoman($month)
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $map[intval($month)] ?? (string)$month;
    }
}
