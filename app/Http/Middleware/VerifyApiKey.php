<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Ambil Kunci Rahasia dari header request.
        $apiKey = $request->header('X-API-KEY');

        // 2. Ambil Kunci Rahasia yang benar dari file .env.
        $correctApiKey = env('INTERNAL_API_KEY');

        // 3. Bandingkan keduanya.
        // Pastikan kuncinya ada dan cocok.
        if (!$apiKey || $apiKey !== $correctApiKey) {
            // Jika tidak cocok, tolak permintaan.
            return response()->json(['message' => 'Unauthorized: Invalid API Key'], 401);
        }

        // 4. Jika cocok, izinkan permintaan untuk melanjutkan.
        return $next($request);
    }
}
