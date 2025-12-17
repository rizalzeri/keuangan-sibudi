<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipSop;
use Carbon\Carbon;

class ArsipSopController extends Controller
{
    public function index()
    {
        $sops = ArsipSop::orderBy('id', 'asc')->get();
        return view('spj.arsip_sop.index', compact('sops'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_sop'      => 'required|string|max:255',
            // nomor_dokumen tidak wajib saat create karena akan di-generate
            'nomor_dokumen' => 'nullable|string|max:255',
            'ruang_lingkup' => 'nullable|string|max:255',
            'status'        => 'nullable|in:Berlaku,Tidak Berlaku',
            'link_gdrive'   => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        // Set default status jika tidak dikirim
        if (!isset($validated['status']) || !$validated['status']) {
            $validated['status'] = 'Berlaku';
        }

        // Jika nomor_dokumen tidak diberikan (create) -> generate otomatis
        if (empty($validated['nomor_dokumen'])) {
            $now = Carbon::now();
            $year = $now->year;
            $month = $now->month;
            // hitung berapa surat pada tahun yang sama (menggunakan created_at)
            $countThisYear = ArsipSop::whereYear('created_at', $year)->count();
            $no = $countThisYear + 1;
            $monthRoman = $this->monthToRoman($month);
            $validated['nomor_dokumen'] = "SOP/{$no}/{$monthRoman}/{$year}";
        }

        $sop = ArsipSop::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'SOP berhasil ditambahkan!',
                'data'    => $sop
            ], 201);
        }

        return back()->with('success', 'SOP berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'nama_sop'      => 'required|string|max:255',
            'nomor_dokumen' => 'nullable|string|max:255',
            'ruang_lingkup' => 'nullable|string|max:255',
            'status'        => 'nullable|in:Berlaku,Tidak Berlaku',
            'link_gdrive'   => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $sop = ArsipSop::find($id);
        if (!$sop) {
            if ($request->ajax()) {
                return response()->json(['success'=>false,'message'=>"SOP dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "SOP dengan ID $id tidak ditemukan");
        }

        // Jika nomor tidak dikirim (atau kosong) -> jangan overwrite nomor lama
        if (empty($validated['nomor_dokumen'])) {
            unset($validated['nomor_dokumen']);
        }

        $sop->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'SOP berhasil diperbarui!',
                'data'    => $sop
            ]);
        }

        return back()->with('success', 'SOP berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $sop = ArsipSop::find($id);
        if (!$sop) {
            if ($request->ajax()) {
                return response()->json(['success'=>false,'message'=>"SOP dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "SOP dengan ID $id tidak ditemukan");
        }

        $sop->delete();

        if ($request->ajax()) {
            return response()->json(['success'=>true,'message'=>'SOP berhasil dihapus!']);
        }

        return back()->with('success', 'SOP berhasil dihapus!');
    }

    /**
     * Helper: konversi bulan (1-12) ke angka Romawi
     */
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
