<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipSop;

class ArsipSopController extends Controller
{
    public function index()
    {
        $sops = ArsipSop::orderBy('id', 'asc')->get();
        return view('spj.arsip_sop.index', compact('sops'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_sop'      => 'required|string|max:255',
            'nomor_dokumen' => 'required|string|max:255',
            'ruang_lingkup' => 'nullable|string|max:255',
            'status'        => 'nullable|in:Berlaku,Tidak Berlaku',
            'link_gdrive'   => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        // Set default status if missing
        if (!isset($validated['status']) || !$validated['status']) {
            $validated['status'] = 'Berlaku';
        }

        $sop = ArsipSop::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'SOP berhasil ditambahkan!',
                'data'    => $sop
            ], 201);
        }

        return back()->with('success', 'SOP berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'nama_sop'      => 'required|string|max:255',
            'nomor_dokumen' => 'required|string|max:255',
            'ruang_lingkup' => 'nullable|string|max:255',
            'status'        => 'nullable|in:Berlaku,Tidak Berlaku',
            'link_gdrive'   => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $sop = ArsipSop::find($id);
        if (!$sop) {
            if ($request->ajax()) {
                return response()->json(['success'=>false,'message'=>"SOP dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "SOP dengan ID $id tidak ditemukan");
        }

        $sop->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'SOP berhasil diperbarui!',
                'data'    => $sop
            ]);
        }

        return back()->with('success', 'SOP berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $sop = ArsipSop::find($id);
        if (!$sop) {
            if ($request->ajax()) {
                return response()->json(['success'=>false,'message'=>"SOP dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "SOP dengan ID $id tidak ditemukan");
        }

        $sop->delete();

        if ($request->ajax()) {
            return response()->json(['success'=>true,'message'=>'SOP berhasil dihapus!']);
        }

        return back()->with('success', 'SOP berhasil dihapus!');
    }
}
