<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheNeracaMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = Auth::id();
        $selectedYear = session('selected_year', date('Y'));
        $cacheKey = "neraca_{$userId}_{$selectedYear}";

        // Hapus cache neraca sebelum request diproses
        Cache::forget($cacheKey);

        return $next($request);
    }
}
