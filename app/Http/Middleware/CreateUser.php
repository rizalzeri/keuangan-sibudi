<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Ekuit;
use App\Models\Profil;
use App\Models\Rekonsiliasi;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $userId = auth()->user()->id;

            $existingEkuit = Ekuit::where('user_id', $userId)->first();
            $profil = Profil::where('user_id', $userId)->first();

            if (!$existingEkuit) {
                Ekuit::create(['user_id' => $userId]);
            }
            if (!$profil) {
                Profil::create(['user_id' => $userId]);
            }

            $rekonsiliasi = Rekonsiliasi::where('user_id', $userId)->first();

            if (!$rekonsiliasi) {
                Rekonsiliasi::insert([
                    ['posisi' => 'Kas di tangan', 'user_id' => $userId],
                    ['posisi' => 'Bank Jateng', 'user_id' => $userId]
                ]);
            }
        }
        return $next($request);
    }
}
