<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArsipSopController extends Controller
{
    public function index()
    {
        // nanti bisa ambil data dari DB, untuk demo kita pakai data dummy di view
        return view('spj.arsip_sop.index');
    }

    public function store(Request $request)
    {
        // contoh server-side: validasi & simpan ke DB
        // $validated = $request->validate([
        //     'nama' => 'required|string',
        //     'nomor' => 'nullable|string',
        //     'ruang_lingkup' => 'nullable|string',
        //     'status' => 'required|in:Berlaku,Tidak',
        //     'gdrive' => 'nullable|url',
        // ]);
        // ArsipSop::create($validated);
    }

    public function update(Request $request, $id)
    {
        // update data
        // $model = ArsipSop::findOrFail($id);
        // $model->update($request->all());
    }

    public function destroy($id)
    {
        // hapus data
        // ArsipSop::findOrFail($id)->delete();
    }
}
