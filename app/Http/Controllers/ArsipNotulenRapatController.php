<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArsipNotulenRapatController extends Controller
{
    public function index()
    {
        // nanti ambil data dari DB; demo pakai dummy di view
        return view('spj.arsip_notulen_rapat.index');
    }

    public function store(Request $request)
    {
        // validasi & simpan ke DB jika diperlukan
    }

    public function update(Request $request, $id)
    {
        // update jika diperlukan
    }

    public function destroy($id)
    {
        // hapus jika diperlukan
    }
}
