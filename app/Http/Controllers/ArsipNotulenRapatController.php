<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipNotulenRapat;

class ArsipNotulenRapatController extends Controller
{
    public function index()
    {
        $items = ArsipNotulenRapat::orderBy('id', 'desc')->get();
        return view('spj.arsip_notulen_rapat.index', compact('items'));
    }

    public function store(Request $request)
    {
        $rules = [
            'tanggal_notulen_rapat' => 'required|date',
            'waktu' => 'nullable|date_format:H:i',
            'tempat' => 'nullable|string|max:255',
            'agenda' => 'nullable|string|max:255',
            'penyelenggara' => 'nullable|string|max:255',
            'link_gdrive' => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $n = ArsipNotulenRapat::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notulen berhasil ditambahkan!',
                'data'    => $n
            ], 201);
        }

        return back()->with('success', 'Notulen berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'tanggal_notulen_rapat' => 'required|date',
            'waktu' => 'nullable|date_format:H:i',
            'tempat' => 'nullable|string|max:255',
            'agenda' => 'nullable|string|max:255',
            'penyelenggara' => 'nullable|string|max:255',
            'link_gdrive' => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $n = ArsipNotulenRapat::find($id);
        if (!$n) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Notulen dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Notulen dengan ID $id tidak ditemukan");
        }

        $n->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Notulen berhasil diperbarui!', 'data' => $n]);
        }

        return back()->with('success', 'Notulen berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $n = ArsipNotulenRapat::find($id);
        if (!$n) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Notulen dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Notulen dengan ID $id tidak ditemukan");
        }

        $n->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Notulen berhasil dihapus!']);
        }

        return back()->with('success', 'Notulen berhasil dihapus!');
    }
}
