<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('jwt_token')) {

            return redirect()->route('login')->withErrors(['login' => 'Пожалуйста, выполните вход']);
        }

        return $next($request);
    }
}
