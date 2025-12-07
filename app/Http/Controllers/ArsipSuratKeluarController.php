<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArsipSuratKeluarController extends Controller
{
    public function index()
    {
        // untuk demo: return view tanpa data DB (dummy di view)
        return view('spj.arsip_surat_keluar.index');
    }

    public function store(Request $request)
    {
        // validasi & simpan
    }

    public function update(Request $request, $id)
    {
        // update
    }

    public function destroy($id)
    {
        // hapus
    }
}
