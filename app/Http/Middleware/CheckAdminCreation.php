<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminCreation
{
    public function handle($request, Closure $next)
    {
        if ($request->is('api/users/create-admin')) {
            return $next($request);
        }

        return auth()->check() ? $next($request) : response()->json(['message' => 'Unauthenticated.'], 401);
    }
}
