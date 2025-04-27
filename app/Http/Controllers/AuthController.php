<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Proses login jika validasi berhasil
        $validated = $validator->validated();
        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Token dan kedaluwarsa
        $token = Str::random(60);
        $tokenExpiration = Carbon::now()->addHour();

        $user->api_token = hash('sha256', $token);
        $user->token_expiration = $tokenExpiration;
        $user->save();

        return response()->json([
            'success' => true,
            'token' => $token,
            'expires_at' => $tokenExpiration
        ]);
    }

    public function verifyToken(Request $request)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['error' => 'Token is required'], 401);
        }

        // Verifikasi token dan kedaluwarsa
        $user = User::where('api_token', hash('sha256', $token))->first();

        $now = Carbon::now('UTC');
        // Gunakan Carbon untuk memeriksa apakah token sudah kedaluwarsa
        if (!$user || $user->token_expiration < $now) {
            return response()->json(['error' => 'Token expired or invalid'], 401);
        }

        return response()->json(['message' => 'Token is valid']);
    }
}
