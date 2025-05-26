<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ExistingMiddleware
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
        if (Auth::guard('frontend')->check() && Auth::guard('frontend')->user()->register_as != 'existing') {
            return redirect()->route('frontend.index');
        }

        return $next($request);
    }
}
