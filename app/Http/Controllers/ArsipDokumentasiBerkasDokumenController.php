<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipDokumentasiBerkasDokumen;
use Illuminate\Support\Collection;

class ArsipDokumentasiBerkasDokumenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = ArsipDokumentasiBerkasDokumen::where('users_id', auth()->id())
            ->orderBy('tanggal_berkas_dokumen','desc')->get();

        // ambil daftar tahun unik untuk filter (server-side)
        $years = $items->map(function($it){
            return $it->tanggal_berkas_dokumen ? $it->tanggal_berkas_dokumen->format('Y') : null;
        })->filter()->unique()->values();

        return view('spj.arsip_dokumentasi_berkas.index', compact('items','years'));
    }

    public function store(Request $request)
    {
        $rules = [
            'tanggal_berkas_dokumen' => 'required|date',
            'nama_dokumen'           => 'nullable|string|max:255',
            'link_gdrive'            => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $validated['users_id'] = auth()->id();

        $item = ArsipDokumentasiBerkasDokumen::create($validated);

        if ($request->ajax()) {
            return response()->json(['success'=>true,'message'=>'Berkas berhasil disimpan','data'=>$item],201);
        }

        return back()->with('success','Berkas berhasil disimpan');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'tanggal_berkas_dokumen' => 'required|date',
            'nama_dokumen'           => 'nullable|string|max:255',
            'link_gdrive'            => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $item = ArsipDokumentasiBerkasDokumen::where('id', $id)
            ->where('users_id', auth()->id())
            ->first();

        if (!$item) {
            if ($request->ajax()) return response()->json(['success'=>false,'message'=>"Item $id tidak ditemukan atau bukan milik Anda"],404);
            return back()->with('error',"Item $id tidak ditemukan atau bukan milik Anda");
        }

        $item->update($validated);

        if ($request->ajax()) return response()->json(['success'=>true,'message'=>'Berkas diperbarui','data'=>$item]);

        return back()->with('success','Berkas diperbarui');
    }

    public function destroy(Request $request, $id)
    {
        $item = ArsipDokumentasiBerkasDokumen::where('id', $id)
            ->where('users_id', auth()->id())
            ->first();

        if (!$item) {
            if ($request->ajax()) return response()->json(['success'=>false,'message'=>"Item $id tidak ditemukan atau bukan milik Anda"],404);
            return back()->with('error',"Item $id tidak ditemukan atau bukan milik Anda");
        }

        $item->delete();

        if ($request->ajax()) return response()->json(['success'=>true,'message'=>'Berkas dihapus']);

        return back()->with('success','Berkas dihapus');
    }
}
