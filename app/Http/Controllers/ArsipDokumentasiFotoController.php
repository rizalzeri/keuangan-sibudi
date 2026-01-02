<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipDokumentasiFoto;

class ArsipDokumentasiFotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = ArsipDokumentasiFoto::where('users_id', auth()->id())
            ->orderBy('id', 'asc')->get();
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

        $validated['users_id'] = auth()->id();

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

        $item = ArsipDokumentasiFoto::where('id', $id)
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
                'message' => 'Dokumentasi berhasil diperbarui!',
                'data'    => $item
            ]);
        }

        return back()->with('success', 'Dokumentasi berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $item = ArsipDokumentasiFoto::where('id', $id)
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
            return response()->json(['success' => true, 'message' => 'Dokumentasi berhasil dihapus!']);
        }

        return back()->with('success', 'Dokumentasi berhasil dihapus!');
    }
}
