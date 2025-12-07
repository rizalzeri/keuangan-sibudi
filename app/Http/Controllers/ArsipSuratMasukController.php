<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArsipSuratMasukController extends Controller
{
    public function index()
    {
        // nanti bisa ambil data dari DB, untuk demo kita pakai data dummy
        return view('spj.arsip_surat_masuk.index');
    }

    public function store(Request $request)
    {
        // validasi & simpan ke DB
        // contoh:
        // $validated = $request->validate([...]);
        // ArsipSurat::create($validated);
        // return redirect()->back()->with('success','Disimpan');
    }

    public function update(Request $request, $id)
    {
        // validasi & update data
        // $model = ArsipSurat::findOrFail($id);
        // $model->update($request->all());
    }

    public function destroy($id)
    {
        // hapus data
        // ArsipSurat::findOrFail($id)->delete();
    }
}
