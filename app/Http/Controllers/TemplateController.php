<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipKategori;

class TemplateController extends Controller
{
    /**
     * Menampilkan halaman yang memuat modal templates
     */
    public function index()
    {
        // ambil semua kategori beserta sub kategori (eager load)
        $categories = ArsipKategori::with(['subCategories' => function($q){
            $q->orderBy('id');
        }])->orderBy('id')->get();

        // ganti 'auth.login' dengan nama view blade Anda (halaman yang berisi modal)
        return view('auth.login', compact('categories'));
    }
}
