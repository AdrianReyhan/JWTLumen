<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;

class AuthenticateApiToken
{
    /**
     * Handle an incoming request.
     * //Memastikan semua request API membawa token yang valid sebelum lanjut ke controller.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        // Ambil token dari header Authorization
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['error' => 'Token is required'], 401);
        }

        // Menghapus "Bearer" dari token
        $token = str_replace('Bearer ', '', $token);

        // Verifikasi token dan kedaluwarsa
        $user = User::where('api_token', hash('sha256', $token))->first();

        if (!$user || $user->token_expiration < Carbon::now()) {
            return response()->json(['error' => 'Token expired or invalid'], 401);
        }
        

        return $next($request);
    }
}
