<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $role = auth()->user()->user_roles_id;
        
        // Jika role = 1 → ADMIN → lanjut ke halaman admin
        if ($role == 1) {
            return $next($request);
        }

        // Jika role = 2 → Bendahara → ke home
        if ($role == 2) {
            return redirect('/');
        }

        // Jika role = 3 → Sekretaris → ke SPJ
        if ($role == 3) {
            return redirect('/spj');
        }

        // fallback jika role tidak dikenal
        return redirect('/');
    }
}
