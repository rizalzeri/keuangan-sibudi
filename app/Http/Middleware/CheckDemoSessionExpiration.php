<?php

namespace App\Http\Middleware;

use App\Services\DemoSandboxService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckDemoSessionExpiration
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->is_demo) {
                // Periksa apakah masa berlaku praktikum 1 jam telah habis
                if ($user->demo_expires_at && now()->greaterThan($user->demo_expires_at)) {
                    $sandboxService = app(DemoSandboxService::class);
                    
                    // Bersihkan seluruh data transaksi dan arsip user demo ini
                    $sandboxService->purgeUserData($user->id);
                    $user->delete();

                    Auth::logout();

                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect('/login')->with('info', 'Waktu praktikum 1 jam Anda telah berakhir. Seluruh data transaksi praktikum telah dibersihkan secara otomatis.');
                }
            }
        }

        return $next($request);
    }
}
