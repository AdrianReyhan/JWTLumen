<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPembaca
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->role !== 'pembaca') {
            return response()->json(['message' => 'Access denied: You must be a admin or pembaca to view.'], 403);
        }
        return $next($request);
    }
}
