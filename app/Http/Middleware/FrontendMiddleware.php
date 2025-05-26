<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Auth;

class FrontendMiddleware
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
        if ($request->direct_login == 1) {
            if (Auth::guard('admin')->check()) {
                $user = User::find($request->id);
                if (isset($user)) {
                    Auth::guard('frontend')->login($user);
                    return redirect()->route('frontend.dashboard');
                }
            }
        }

        if (!Auth::guard('frontend')->check()) {
            return redirect()->route('frontend.login');
        }

        return $next($request);
    }
}
