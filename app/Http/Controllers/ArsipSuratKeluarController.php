<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipSuratKeluar;

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
            'nomor_dokumen' => 'required|string|max:255',
            'tujuan'        => 'nullable|string|max:255',
            'judul_surat'   => 'nullable|string|max:255',
            'isi'           => 'nullable|string',
            'link_gdrive'   => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $surat = ArsipSuratKeluar::create($validated);

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
            'nomor_dokumen' => 'required|string|max:255',
            'tujuan'        => 'nullable|string|max:255',
            'judul_surat'   => 'nullable|string|max:255',
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
}
