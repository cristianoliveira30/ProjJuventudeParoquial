<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFirstAccessPassword
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->password_verify) {
            if (
                !$request->routeIs('first.access') &&
                !$request->routeIs('email.verify*') &&
                !$request->routeIs('user.profile.password.update') &&
                !$request->routeIs('logout')
            ) {
                return redirect()->route('first.access');
            }
        }

        return $next($request);
    }
}
