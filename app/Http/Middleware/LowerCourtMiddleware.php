<?php

namespace App\Http\Middleware;

use Closure;

class LowerCourtMiddleware
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
        if (Auth::guard('frontend')->check() && Auth::guard('frontend')->user()->register_as != 'lc') {
            return redirect()->route('frontend.index');
        }

        return $next($request);
    }
}
