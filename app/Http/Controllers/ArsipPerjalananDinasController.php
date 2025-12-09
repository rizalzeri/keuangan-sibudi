<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipPerjalananDinas;

class ArsipPerjalananDinasController extends Controller
{
    public function index()
    {
        $items = ArsipPerjalananDinas::orderBy('id', 'asc')->get();
        return view('spj.arsip_perjalanan_dinas.index', compact('items'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nomor_dokumen' => 'required|string|max:255',
            'tanggal_perjalanan_dinas' => 'nullable|date',
            'kegiatan' => 'nullable|string|max:255',
            'tempat' => 'nullable|string|max:255',
            'transport' => 'nullable|string|max:255',
            'link_gdrive' => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $pd = ArsipPerjalananDinas::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Perjalanan dinas berhasil ditambahkan!',
                'data'    => $pd
            ], 201);
        }

        return back()->with('success', 'Perjalanan dinas berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'nomor_dokumen' => 'required|string|max:255',
            'tanggal_perjalanan_dinas' => 'nullable|date',
            'kegiatan' => 'nullable|string|max:255',
            'tempat' => 'nullable|string|max:255',
            'transport' => 'nullable|string|max:255',
            'link_gdrive' => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $pd = ArsipPerjalananDinas::find($id);
        if (!$pd) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Perjalanan dinas dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Perjalanan dinas dengan ID $id tidak ditemukan");
        }

        $pd->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Perjalanan dinas berhasil diperbarui!',
                'data'    => $pd
            ]);
        }

        return back()->with('success', 'Perjalanan dinas berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $pd = ArsipPerjalananDinas::find($id);
        if (!$pd) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Perjalanan dinas dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Perjalanan dinas dengan ID $id tidak ditemukan");
        }

        $pd->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Perjalanan dinas berhasil dihapus!']);
        }

        return back()->with('success', 'Perjalanan dinas berhasil dihapus!');
    }
}
