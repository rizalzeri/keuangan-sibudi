<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipKategori;

use App\Services\DemoSandboxService;

class TemplateController extends Controller
{
    /**
     * Menampilkan halaman yang memuat modal templates atau otomatis login jika membawa token praktikum
     */
    public function index(Request $request, DemoSandboxService $sandboxService)
    {
        // Jika terdapat parameter ?token=..., proses login otomatis praktikum
        if ($request->filled('token')) {
            $token = trim($request->query('token'));
            try {
                $user = $sandboxService->authenticateByToken($token);

                if ($user) {
                    return redirect('/spj')->with('success', 'Selamat datang di Sesi Praktikum PortalBUMDes Academy! Sesi Anda aktif selama 1 jam.');
                }

                return redirect('/login')->with('error', 'Token praktikum PortalBUMDes tidak valid atau tidak ditemukan di database portal.');
            } catch (\Throwable $e) {
                return redirect('/login')->with('error', 'Error praktikum: ' . $e->getMessage());
            }
        }

        // ambil semua kategori beserta sub kategori (eager load)
        $categories = ArsipKategori::with(['subCategories' => function($q){
            $q->orderBy('id');
        }])->orderBy('id')->get();

        // ganti 'auth.login' dengan nama view blade Anda (halaman yang berisi modal)
        return view('auth.login', compact('categories'));
    }
}
