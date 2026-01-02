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

        $daysRemaining = null;

        if ($user && $user->tgl_langganan) {
            // pastikan format tanggal sesuai: 'Y-m-d'
            try {
                $tgl_langganan = Carbon::createFromFormat('Y-m-d', $user->tgl_langganan)->startOfDay();
            } catch (\Exception $e) {
                // jika format beda, coba parse otomatis
                $tgl_langganan = Carbon::parse($user->tgl_langganan)->startOfDay();
            }

            $now = Carbon::now();

            if ($tgl_langganan->isPast()) {
                // Jika langganan sudah berakhir, ubah status menjadi false
                User::where('id', $user->id)->update(['status' => false]);
                $daysRemaining = 0;
            } else {
                // Hitung sisa hari (integer)
                $daysRemaining = $now->diffInDays($tgl_langganan);
            }
        }

        // Share variable ke semua view supaya sidebar (dan view lain) bisa mengaksesnya
        // Jika tidak ada user atau tgl_langganan null, nilai tetap null
        view()->share('langganan_days_remaining', $daysRemaining);

        return $next($request);
    }
}
