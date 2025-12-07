<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArsipDokumentasiVideoController extends Controller
{
    public function index()
    {
        return view('spj.arsip_dokumentasi_video.index');
    }

    public function store(Request $request)
    {
        // proses simpan ke DB (optional)
    }

    public function update(Request $request, $id)
    {
        // proses update ke DB
    }

    public function destroy($id)
    {
        // proses delete
    }
}
