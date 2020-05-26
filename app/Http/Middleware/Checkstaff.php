<?php

namespace App\Http\Middleware;

use Closure;

class Checkstaff
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
        if ($request->session()->get('userType') !== 'staff') {
            // user value cannot be found in session
            return abort(403);
        }

        return $next($request);
    }
}
