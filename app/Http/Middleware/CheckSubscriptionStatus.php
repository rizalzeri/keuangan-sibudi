<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->tgl_langganan) {
            $tgl_langganan = Carbon::createFromFormat('Y-m-d', $user->tgl_langganan);
            if ($tgl_langganan->isPast()) {
                // Jika langganan sudah berakhir, ubah status menjadi false
                User::where('id', $user->id)->update(['status' => false]);
            }
        }


        return $next($request);
    }
}
