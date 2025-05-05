<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPenulis
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->role !== 'penulis') {
            return response()->json(['message' => 'Access denied: You must be a admin or penulis to post.'], 403);
        }
        return $next($request);
    }
}
