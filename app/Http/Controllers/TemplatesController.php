<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipKategori; // pastikan model Category ada dan relasi subCategories

class TemplatesController extends Controller
{
    public function index()
    {
        // load categories + relasi subCategories
        $categories = ArsipKategori::with('subCategories')->get();

        return view('admin.digital_produk.index', compact('categories'));
    }
}
