<?php

namespace App\Http\Middleware;

use Closure;

class Checkstudent
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
        if ($request->session()->get('userType') !== 'student') {
            // user might be staff
            return redirect('/staff');
        }
        
        return $next($request);
    }
}
