<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipDokumentasiFoto;

class ArsipDokumentasiFotoController extends Controller
{
    public function index()
    {
        // ambil semua item, urut berdasarkan id
        $items = ArsipDokumentasiFoto::orderBy('id', 'asc')->get();
        return view('spj.arsip_dokumentasi_foto.index', compact('items'));
    }

    public function store(Request $request)
    {
        $rules = [
            'tanggal_foto' => 'required|date',
            'kegiatan'     => 'nullable|string|max:255',
            'link_gdrive'  => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $item = ArsipDokumentasiFoto::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Dokumentasi berhasil ditambahkan!',
                'data'    => $item
            ], 201);
        }

        return back()->with('success', 'Dokumentasi berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'tanggal_foto' => 'required|date',
            'kegiatan'     => 'nullable|string|max:255',
            'link_gdrive'  => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $item = ArsipDokumentasiFoto::find($id);
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
                'message' => 'Dokumentasi berhasil diperbarui!',
                'data'    => $item
            ]);
        }

        return back()->with('success', 'Dokumentasi berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $item = ArsipDokumentasiFoto::find($id);
        if (!$item) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Item dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Item dengan ID $id tidak ditemukan");
        }

        $item->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Dokumentasi berhasil dihapus!']);
        }

        return back()->with('success', 'Dokumentasi berhasil dihapus!');
    }
}
