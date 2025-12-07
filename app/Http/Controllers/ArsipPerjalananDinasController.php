<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArsipPerjalananDinasController extends Controller
{
    public function index()
    {
        return view('spj.arsip_perjalanan_dinas.index');
    }

    public function store(Request $request)
    {
        // proses simpan ke DB
    }

    public function update(Request $request, $id)
    {
        // proses update ke DB
    }

    public function destroy($id)
    {
        // proses hapus
    }
}
    