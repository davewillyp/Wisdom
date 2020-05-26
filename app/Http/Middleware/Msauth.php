<?php

namespace App\Http\Middleware;

use Closure;

class Msauth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->session()->exists('accessToken')) {
            // user value cannot be found in session
            return redirect('/signin');
        }

        return $next($request);
    
    }
}
