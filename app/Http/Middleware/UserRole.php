<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $userRole = auth()->user()->user_roles_id;

        // cek apakah role user ada pada parameter middleware
        if (!in_array($userRole, $roles)) {
            return redirect('/unauthorized');
        }

        return $next($request);
    }
}
