<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use Illuminate\Validation\Rule;
use Validator;
use Hash;

class OtpForgotPasswordController extends Controller
{
    public function otpPasswordReset(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {

            $digits = 6;
            $otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);

            $user = User::where('phone', $request->phone)->first();

            if (isset($user)) {
                $user->update(['otp' => $otp]);

                $data = array(
                    "phone" => '+92' . $user->phone,
                    "otp" => $user->otp,
                    "event_id" => 83,
                );

                sendMessageAPI($data);

                return response()->json(['status' => 1, 'message' => 'success']);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'The phone number that you have entered is incorrect.',
                ], 400);
            }
        }

        return view('auth.passwords.phone');
    }

    public function otpPasswordResetForm(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {

            $user = User::where('otp', $request->otp)->first();

            if (isset($user)) {
                $rules = [
                    'otp' => ['required', 'numeric'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return response()->json([
                        'errors' => $validator->errors(),
                    ], 400);
                }

                $user->otp = NULL;
                $user->password = Hash::make($request->password);
                $user->save();
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'The OTP you have entered is invalid or expired.',
                ], 400);
            }

            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return view('auth.passwords.otp-password-reset');
    }
}
