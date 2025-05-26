<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class DefaultPasswordMiddleware
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
        if (Auth::guard('admin')->user()->default_password_changed == 0) {
            return redirect()->route('admin.dashboard')->with('error', 'Your default password has not been changed. Please update your account with new password.');
        }
        return $next($request);
    }
}
