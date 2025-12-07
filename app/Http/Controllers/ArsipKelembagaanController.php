<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipLembaga;

class ArsipKelembagaanController extends Controller
{
    public function index()
    {
        $data_arsip_lembaga = ArsipLembaga::orderBy('id', 'asc')->get();
        return view('spj.arsip_kelembagaan.index', compact('data_arsip_lembaga'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_dokumen' => 'required|string',
            'nomor'        => 'nullable|string',
            'status'       => 'required|in:Berlaku,Tidak Berlaku',
            'link_gdrive'  => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $arsip = ArsipLembaga::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan!',
                'data'    => $arsip
            ], 201);
        }

        return back()->with('success', 'Data berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'nama_dokumen' => 'required|string',
            'nomor'        => 'nullable|string',
            'status'       => 'required|in:Berlaku,Tidak Berlaku',
            'link_gdrive'  => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        // cari data berdasarkan ID
        $arsip = ArsipLembaga::where('id', $id)->first();

        // jika tidak ada → jangan error 404, tapi beri respon normal
        if (!$arsip) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => "Data dengan ID $id tidak ditemukan"
                ], 404);
            }
            return back()->with('error', "Data dengan ID $id tidak ditemukan");
        }

        // update data
        $arsip->update($validated);

        // response sukses untuk AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui!',
                'data'    => $arsip
            ]);
        }

        return back()->with('success', 'Data berhasil diperbarui!');
    }


    public function destroy(Request $request, $id)
    {
        // cari data dulu tanpa error
        $arsip = ArsipLembaga::where('id', $id)->first();

        // jika data tidak ditemukan → balikan respon santai
        if (!$arsip) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => "Data dengan ID $id tidak ditemukan"
                ], 404);
            }

            return back()->with('error', "Data dengan ID $id tidak ditemukan");
        }

        // jika ada → hapus
        $arsip->delete();

        // respon AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus!'
            ]);
        }

        return back()->with('success', 'Data berhasil dihapus!');
    }

}
