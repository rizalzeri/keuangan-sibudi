<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipBeritaAcara;

class ArsipBeritaAcaraController extends Controller
{
    public function index()
    {
        $items = ArsipBeritaAcara::orderBy('id', 'asc')->get();
        return view('spj.arsip_berita_acara.index', compact('items'));
    }

    public function store(Request $request)
    {
        $rules = [
            'judul_berita_acara' => 'required|string|max:255',
            'tanggal_peristiwa'  => 'required|date',
            'deskripsi'          => 'nullable|string',
            'link_gdrive'        => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $ba = ArsipBeritaAcara::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Berita acara berhasil ditambahkan!',
                'data'    => $ba
            ], 201);
        }

        return back()->with('success', 'Berita acara berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'judul_berita_acara' => 'required|string|max:255',
            'tanggal_peristiwa'  => 'required|date',
            'deskripsi'          => 'nullable|string',
            'link_gdrive'        => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $ba = ArsipBeritaAcara::find($id);
        if (!$ba) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Berita acara dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Berita acara dengan ID $id tidak ditemukan");
        }

        $ba->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Berita acara berhasil diperbarui!',
                'data'    => $ba
            ]);
        }

        return back()->with('success', 'Berita acara berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $ba = ArsipBeritaAcara::find($id);
        if (!$ba) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Berita acara dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Berita acara dengan ID $id tidak ditemukan");
        }

        $ba->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Berita acara berhasil dihapus!']);
        }

        return back()->with('success', 'Berita acara berhasil dihapus!');
    }
}
