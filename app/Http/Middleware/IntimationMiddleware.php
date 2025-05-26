<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IntimationMiddleware
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
        if (Auth::guard('frontend')->check() && Auth::guard('frontend')->user()->register_as != 'intimation') {
            return redirect()->route('frontend.index');
        }

        return $next($request);
    }
}
