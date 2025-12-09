<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipDokumentasiVideo;

class ArsipDokumentasiVideoController extends Controller
{
    public function index()
    {
        $items = ArsipDokumentasiVideo::orderBy('id', 'asc')->get();
        return view('spj.arsip_dokumentasi_video.index', compact('items'));
    }

    public function store(Request $request)
    {
        $rules = [
            'tanggal_video' => 'required|date',
            'kegiatan'      => 'nullable|string|max:255',
            'link_gdrive'   => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $item = ArsipDokumentasiVideo::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Dokumentasi video berhasil ditambahkan!',
                'data'    => $item
            ], 201);
        }

        return back()->with('success', 'Dokumentasi video berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'tanggal_video' => 'required|date',
            'kegiatan'      => 'nullable|string|max:255',
            'link_gdrive'   => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $item = ArsipDokumentasiVideo::find($id);
        if (!$item) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Item dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Item dengan ID $id tidak ditemukan");
        }

        $item->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Dokumentasi video berhasil diperbarui!',
                'data'    => $item
            ]);
        }

        return back()->with('success', 'Dokumentasi video berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $item = ArsipDokumentasiVideo::find($id);
        if (!$item) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Item dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Item dengan ID $id tidak ditemukan");
        }

        $item->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Dokumentasi video berhasil dihapus!']);
        }

        return back()->with('success', 'Dokumentasi video berhasil dihapus!');
    }
}
