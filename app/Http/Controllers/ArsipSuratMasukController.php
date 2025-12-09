<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipSuratMasuk;

class ArsipSuratMasukController extends Controller
{
    public function index()
    {
        $surats = ArsipSuratMasuk::orderBy('id', 'asc')->get();
        return view('spj.arsip_surat_masuk.index', compact('surats'));
    }

    public function store(Request $request)
    {
        $rules = [
            'pengirim'    => 'required|string|max:255',
            'judul_surat' => 'nullable|string|max:255',
            'isi'         => 'nullable|string',
            'link_gdrive' => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $surat = ArsipSuratMasuk::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Surat berhasil ditambahkan!',
                'data'    => $surat
            ], 201);
        }

        return back()->with('success', 'Surat berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'pengirim'    => 'required|string|max:255',
            'judul_surat' => 'nullable|string|max:255',
            'isi'         => 'nullable|string',
            'link_gdrive' => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $surat = ArsipSuratMasuk::find($id);
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
                'message' => 'Surat berhasil diperbarui!',
                'data'    => $surat
            ]);
        }

        return back()->with('success', 'Surat berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $surat = ArsipSuratMasuk::find($id);
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
                'message' => 'Surat berhasil dihapus!'
            ]);
        }

        return back()->with('success', 'Surat berhasil dihapus!');
    }
}
