<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use Hash;
use Validator;

use App\Admin;
use App\Application;
use App\LowerCourt;
use App\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            if (Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => 1])) {
                return redirect()->route('admin.dashboard');
            } else {
                Session::flash('error_message', 'Invalid Email or Password');
                return redirect()->back();
            }
        }
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $rules = [
            'password' => 'required|string|min:8|confirmed|max:32',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update(['default_password_changed' => 1]);

        return response()->json(['status' => 1, 'message' => 'Password has been updated successfully.']);
    }

    public function settings(Request $request)
    {
        return view('admin.settings');
    }

    public function checkPassword(Request $request)
    {
        $data = $request->all();
        if (Hash::check($data['current_password'], Auth::guard('admin')->user()->password)) {
            echo "true";
        } else {
            echo "false";
        }
    }

    public function updatePassword(Request $request)
    {
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min:6|max:32',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $data = $request->all();
        if (Hash::check($data['current_password'], Auth::guard('admin')->user()->password)) {
            if ($data['new_password'] == $data['confirm_password']) {
                Admin::where('id', Auth::guard('admin')->user()->id)->update(['password' => bcrypt($data['new_password'])]);
                Session::flash('success_message', 'Password Has Been Updated Successfully!');
                return redirect()->back();
            } else {
                Session::flash('error_message', 'New Password & Confirm Password NOT MATCH');
                return redirect()->back();
            }
        } else {
            Session::flash('error_message', 'Your Current Password is INCORRECT');
            return redirect()->back();
        }

        return redirect()->back();
    }
}
