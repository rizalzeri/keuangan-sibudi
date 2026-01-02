<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipLembaga;
use Illuminate\Support\Facades\Auth;

class ArsipKelembagaanController extends Controller
{
    public function __construct()
    {
        // pastikan hanya user terautentikasi yang bisa akses
        $this->middleware('auth');
    }

    public function index()
    {
        // hanya ambil arsip milik user yang sedang login
        $userId = auth()->id();

        $data_arsip_lembaga = ArsipLembaga::where('users_id', $userId)
            ->orderBy('id', 'asc')
            ->get();

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

        // isi users_id otomatis dari user yang login
        $validated['users_id'] = auth()->id();

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

        // ambil data
        $arsip = ArsipLembaga::find($id);

        // cek ada & ownership
        if (!$arsip) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Data dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Data dengan ID $id tidak ditemukan");
        }

        if ($arsip->users_id !== auth()->id()) {
            // forbidden — user mencoba akses data bukan miliknya
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Anda tidak punya hak untuk mengubah data ini"], 403);
            }
            return back()->with('error', 'Anda tidak punya hak untuk mengubah data ini');
        }

        // update
        $arsip->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui!', 'data' => $arsip]);
        }

        return back()->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $arsip = ArsipLembaga::find($id);

        if (!$arsip) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Data dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Data dengan ID $id tidak ditemukan");
        }

        if ($arsip->users_id !== auth()->id()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Anda tidak punya hak untuk menghapus data ini"], 403);
            }
            return back()->with('error', 'Anda tidak punya hak untuk menghapus data ini');
        }

        $arsip->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus!']);
        }

        return back()->with('success', 'Data berhasil dihapus!');
    }
}
