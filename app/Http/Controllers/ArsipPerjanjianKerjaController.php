<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArsipPerjanjianKerjaController extends Controller
{
    public function index()
    {
        // untuk demo: return view (data dummy ada di view)
        return view('spj.arsip_perjanjian_kerja.index');
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
