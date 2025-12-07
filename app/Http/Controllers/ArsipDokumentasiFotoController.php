<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArsipDokumentasiFotoController extends Controller
{
    public function index()
    {
        return view('spj.arsip_dokumentasi_foto.index');
    }

    public function store(Request $request)
    {
        // nanti untuk simpan DB
    }

    public function update(Request $request, $id)
    {
        // nanti untuk update DB
    }

    public function destroy($id)
    {
        // nanti untuk delete DB
    }
}
