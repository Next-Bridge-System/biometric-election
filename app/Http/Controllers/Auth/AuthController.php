<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {

            $data = $request->all();

            $rules = [
                'password' => ['required', 'string', 'min:8'],
            ];

            if ($request->login_from == 'phone') {
                $rules += [
                    'phone' => ['required', 'numeric'],
                ];
                $user = User::where('phone', $data['phone'])->first();
            }

            if ($request->login_from == 'email') {
                $rules += [
                    'email' => ['required', 'string', 'email', 'max:255'],
                ];
                $user = User::where('email', $data['email'])->first();
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            if (isset($user)) {

                $checkPassword = Hash::check(request('password'), $user->password);
                if ($checkPassword == TRUE) {

                    $request->session()->forget('otp_session');
                    if (empty($request->session()->get('otp_session'))) {
                        $user->fill(['user_id' => $user->id]);
                        $request->session()->put('otp_session', $user->id);
                    }

                    return response()->json(['status' => 1, 'message' => 'success']);
                } else {
                    return response()->json([
                        'status' => false,
                        'error' => 'The password that you have entered is incorrect.',
                    ], 400);
                }
            } else {

                if ($request->login_from == 'phone') {
                    $message = 'The phone number you have entered is not valid.';
                } else {
                    $message = 'The email address you have entered is not valid.';
                }

                return response()->json([
                    'status' => false,
                    'error' => $message,
                ], 400);
            }
        }

        return view('auth.login');
    }

    public function register(Request $request)
    {
        // abort(403, '<strong>Notice:</strong> The registration is currently closed until January 12, 2025.');

        if ($request->isMethod('post')) {

            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'father_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone' => ['required', 'unique:users', 'regex:/(3)[0-9]{9}/'],
                'cnic_no' => ['required', 'unique:users', 'min:15'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                // 'register_as' => ['required', 'in:intimation,lc'],
            ];

            $messages = [
                'cnic_no.required' => 'The cnic number field is required.',
                'cnic_no.min' => 'The cnic number you have entered is invalid.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            // $name = $request->fname . ' ' . $request->lname;

            $data = [
                'name' => $request->name,
                'father_name' => $request->father_name,
                // 'fname' => $request->fname,
                // 'lname' => $request->lname,
                'email' => $request->email,
                'phone' => $request->phone,
                'cnic_no' => $request->cnic_no,
                'password' => Hash::make($request->password),
                'register_as' => $request->register_as,
                'access_token' => getAccessToken(),
            ];

            $user = User::create($data);

            $request->session()->forget('otp_session');
            if (empty($request->session()->get('otp_session'))) {
                $user->fill(['user_id' => $user->id]);
                $request->session()->put('otp_session', $user->id);
            }

            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return view('auth.register');
    }

    private function sendOtp($user)
    {
        $data = array(
            "phone" => '+92' . $user->phone,
            "otp" => $user->otp,
            "event_id" => 79,
        );

        sendMessageAPI($data);

        $mailData = [
            'subject' => 'PUNJAB BAR COUNCIL - OTP',
            'name' => $user->name,
            'description' => '<p>Your OTP is ' . $user->otp . '. Please do not share this OTP with others for your privacy.</p>',
        ];

        sendMailToUser($mailData, $user);
    }

    public function otp(Request $request)
    {
        if ($request->isMethod('post')) {
            $otp = $request->otp;
            $user = User::where('otp', $otp)->first();

            if (isset($user)) {
                return redirect()->route($this->makeLogin($user));
            } else {
                return redirect()->back()->with('error', 'The OTP you have entered is invalid or expired.');
            }
        }

        $otp_session = $request->session()->get('otp_session');

        if ($otp_session == NULL) {
            return redirect()->route('frontend.login');
        }

        $user = User::findOrFail($otp_session);

        if ($user->phone_verified_at == null) {
            $digits = 6;
            $otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            $user->update(['otp' => $otp]);
            $this->sendOtp($user);

            return view('auth.opt', compact('user'));
        } else {
            return redirect()->route($this->makeLogin($user));
        }
    }

    public function logout()
    {
        Auth::guard('frontend')->logout();
        return redirect()->route('frontend.login');
    }

    public function resendOtp(Request $request)
    {
        $user = User::find($request->session()->get('otp_session'));
        $this->sendOtp($user);
    }

    private function makeLogin($user)
    {
        Auth::guard('frontend')->login($user);
        if ($user->phone_verified_at == NULL) {
            $user->phone_verified_at = Carbon::now();
            $user->save();
        }
        $user->otp = NULL;
        $user->save();

        $route_name = 'frontend.dashboard';
        return $route_name;
    }
}
