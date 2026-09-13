<?php

namespace App\Http\Controllers;

use App\Services\DemoSandboxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemoController extends Controller
{
    /**
     * Reset data praktikum demo kembali ke kondisi awal dan perpanjang waktu 1 jam.
     */
    public function reset(Request $request, DemoSandboxService $sandboxService)
    {
        $user = Auth::user();

        if (!$user || !$user->is_demo) {
            return redirect()->back()->with('error', 'Aksi ini hanya tersedia untuk akun sesi praktikum.');
        }

        $sandboxService->resetPracticeData($user);

        return redirect()->back()->with('success', 'Data praktikum berhasil di-reset! Sesi latihan Anda telah diperbarui menjadi 1 jam.');
    }
}
