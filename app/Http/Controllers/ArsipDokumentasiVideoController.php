<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipDokumentasiVideo;

class ArsipDokumentasiVideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = ArsipDokumentasiVideo::where('users_id', auth()->id())
            ->orderBy('id', 'asc')->get();
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

        $validated['users_id'] = auth()->id();

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

        $item = ArsipDokumentasiVideo::where('id', $id)
            ->where('users_id', auth()->id())
            ->first();

        if (!$item) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Item dengan ID $id tidak ditemukan atau bukan milik Anda"], 404);
            }
            return back()->with('error', "Item dengan ID $id tidak ditemukan atau bukan milik Anda");
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
        $item = ArsipDokumentasiVideo::where('id', $id)
            ->where('users_id', auth()->id())
            ->first();

        if (!$item) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Item dengan ID $id tidak ditemukan atau bukan milik Anda"], 404);
            }
            return back()->with('error', "Item dengan ID $id tidak ditemukan atau bukan milik Anda");
        }

        $item->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Dokumentasi video berhasil dihapus!']);
        }

        return back()->with('success', 'Dokumentasi video berhasil dihapus!');
    }
}
