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
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6', 
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $validated = $validator->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

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
            'expired_at' => $tokenExpiration
        ]);
    }

    public function logout(Request $request)
    {
        // Mengambil token dari header Authorization
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['error' => 'Token is required'], 401);
        }

        // Menghapus "Bearer " dari token jika ada
        $token = str_replace('Bearer ', '', $token);

        // Mencari pengguna berdasarkan token yang diberikan
        $user = User::where('api_token', hash('sha256', $token))->first();

        if (!$user) {
            return response()->json(['error' => 'Token not found'], 401);
        }

        $user->api_token = null;  
        $user->token_expiration = null; 
        $user->save();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me(Request $request)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['error' => 'Token is required'], 401);
        }

        $token = str_replace('Bearer ', '', $token);

        $user = User::where('api_token', hash('sha256', $token))->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $now = Carbon::now('UTC');
        if ($user->token_expiration < $now) {
            return response()->json(['error' => 'Token expired'], 401);
        }

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    // public function verifyToken(Request $request)
    // {
    //     $token = $request->header('Authorization');

    //     if (!$token) {
    //         return response()->json(['error' => 'Token is required'], 401);
    //     }

    //     $user = User::where('api_token', hash('sha256', $token))->first();

    //     $now = Carbon::now('UTC');
    //     if (!$user || $user->token_expiration < $now) {
    //         return response()->json(['error' => 'Token expired or invalid'], 401);
    //     }

    //     return response()->json(['message' => 'Token is valid']);
    // }
}
