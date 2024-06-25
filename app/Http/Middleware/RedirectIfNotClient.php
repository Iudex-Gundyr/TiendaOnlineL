<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotClient
{
    public function handle($request, Closure $next, $guard = 'cliente')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect('/productos'); // Cambia esto a la ruta deseada
        }

        return $next($request);
    }
}
