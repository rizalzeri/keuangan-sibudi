<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArsipDokumentasiBerkasController extends Controller
{
    public function index()
    {
        return view('spj.arsip_dokumentasi_berkas.index');
    }

    public function store(Request $request)
    {
        // validasi & simpan DB
    }

    public function update(Request $request, $id)
    {
        // update DB
    }

    public function destroy($id)
    {
        // delete DB
    }
}
