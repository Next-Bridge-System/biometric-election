<?php

namespace App\Http\Middleware;

use Closure;
use App\Application;
use App\HighCourt;
use App\LowerCourt;
use Illuminate\Support\Facades\Auth;

class AcceptApplicationMiddleware
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
        $user = Auth::guard('frontend')->user();

        if ($user->register_as == 'intimation') {
            $intimation = Application::where('user_id', $user->id)->firstOrFail();

            if ($intimation->is_accepted == 1) {
                $request->session()->forget('payment_step_7');
                return redirect()->route('frontend.intimation.create-step-7', $intimation->id);
            }
        }

        if ($user->register_as == 'lc') {
            $lc = LowerCourt::where('user_id', $user->id)->first();

            if (isset($lc) && $lc->is_final_submitted == 1) {
                return redirect()->route('frontend.lower-court.show', $lc->id);
            }
        }

        if ($user->register_as == 'hc') {
            $hc = HighCourt::where('user_id', $user->id)->first();

            if (isset($hc) && $hc->is_final_submitted == 1) {
                return redirect()->route('frontend.high-court.show', $hc->id);
            }
        }

        return $next($request);
    }
}
