<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArsipBeritaAcaraController extends Controller
{
    public function index()
    {
        // untuk demo: return view (data dummy ada di view)
        return view('spj.arsip_berita_acara.index');
    }

    public function store(Request $request)
    {
        // validasi & simpan ke DB jika diperlukan
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
