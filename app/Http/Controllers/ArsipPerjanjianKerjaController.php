<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipPerjanjianKerja;

class ArsipPerjanjianKerjaController extends Controller
{
    public function index()
    {
        $items = ArsipPerjanjianKerja::orderBy('id', 'asc')->get();
        return view('spj.arsip_perjanjian_kerja.index', compact('items'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nomor_dokumen'     => 'required|string|max:255',
            'pihak'             => 'nullable|string|max:255',
            'bentuk_kerja_sama' => 'nullable|string|max:255',
            'deskripsi'         => 'nullable|string',
            'durasi'            => 'nullable|string|max:255',
            'link_gdrive'       => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $item = ArsipPerjanjianKerja::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Perjanjian kerja berhasil ditambahkan!',
                'data'    => $item
            ], 201);
        }

        return back()->with('success', 'Perjanjian kerja berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'nomor_dokumen'     => 'required|string|max:255',
            'pihak'             => 'nullable|string|max:255',
            'bentuk_kerja_sama' => 'nullable|string|max:255',
            'deskripsi'         => 'nullable|string',
            'durasi'            => 'nullable|string|max:255',
            'link_gdrive'       => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $item = ArsipPerjanjianKerja::find($id);
        if (!$item) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Perjanjian kerja dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Perjanjian kerja dengan ID $id tidak ditemukan");
        }

        $item->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Perjanjian kerja berhasil diperbarui!', 'data' => $item]);
        }

        return back()->with('success', 'Perjanjian kerja berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $item = ArsipPerjanjianKerja::find($id);
        if (!$item) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Perjanjian kerja dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Perjanjian kerja dengan ID $id tidak ditemukan");
        }

        $item->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Perjanjian kerja berhasil dihapus!']);
        }

        return back()->with('success', 'Perjanjian kerja berhasil dihapus!');
    }
}
