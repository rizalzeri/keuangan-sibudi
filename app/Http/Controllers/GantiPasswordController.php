<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\GantiPasswordEmail;
use Illuminate\Support\Facades\Mail;

class GantiPasswordController extends Controller
{
    public function index()
    {
        return view('auth.email');
    }
    public function kirimEmail(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'no_telepon' => 'required|string|max:15',
            'pesan' => 'required|string|max:500',
        ]);

        // Data yang akan dikirimkan ke email
        $details = [
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'pesan' => $request->pesan,
            'detail' => $request->detail,
            'url' => '/ganti-password' // Ganti dengan URL halaman ganti password
        ];

        // Kirim email
        Mail::to('dany.dwin@gmail.com')->send(new GantiPasswordEmail($details));

        return back()->with('success', 'Email telah dikirim! Mohon tunggu pesan selanjutnya 1-2 hari');
    }

    public function kontak()
    {
        return view('bantuan.index');
    }
}
