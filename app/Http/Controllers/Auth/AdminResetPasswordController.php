<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Providers\RouteServiceProvider;

use Password;
use Auth;


class AdminResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/admin/login';

    public function __construct()
    {
        $this->middleware('guest:admin');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    protected function broker() {
        return Password::broker('admins');
    }

    public function showResetForm(Request $request, $token = null) {
        return view('auth.passwords.reset-admin')
            ->with(['token' => $token, 'email' => $request->email]
            );
    }
}
