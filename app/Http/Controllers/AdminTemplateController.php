<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipKategori;
use App\Models\ArsipSubKategori;

class AdminTemplateController extends Controller
{
    public function index()
    {
        // Semua kategori (untuk dropdown) dan semua sub kategori untuk tabel
        $categories = ArsipKategori::orderBy('kategori')->get();
        $subCategories = ArsipSubKategori::with('kategori')->orderBy('id')->get();

        return view('admin.produk_digital.index', compact('categories', 'subCategories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori_id' => 'required|exists:arsip_kategori,id',
            'sub_kategori' => 'required|string|max:150',
            'link' => 'nullable|string|max:255',
        ]);

        ArsipSubKategori::create($data);

        return redirect()->route('admin.produk_digital.index')->with('success', 'Produk digital berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $item = ArsipSubKategori::findOrFail($id);

        $data = $request->validate([
            'kategori_id' => 'required|exists:arsip_kategori,id',
            'sub_kategori' => 'required|string|max:150',
            'link' => 'nullable|string|max:255',
        ]);

        $item->update($data);

        return redirect()->route('admin.produk_digital.index')->with('success', 'Produk digital berhasil diperbarui.');
    }

  public function destroy($id)
    {
        $item = ArsipSubKategori::findOrFail($id);
        $item->delete();

        return response()->json(['status' => 'ok'], 200);
    }


}
