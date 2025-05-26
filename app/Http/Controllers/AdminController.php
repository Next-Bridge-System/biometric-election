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
        $intimation_data = Application::where('is_accepted', 1)->where('application_type', 6);
        $intimation = [
            'total_applications' => $intimation_data->count(),
            'completed_applications' => $intimation_data->where('application_status', 1)->count(),
            'in_progress_applications' => $intimation_data->where('application_status', 7)->orWhere('is_accepted', 0)->count(),
            'suspended_applications' => $intimation_data->where('application_status', 2)->count(),
            'died_applications' => $intimation_data->where('application_status', 3)->count(),
            'removed_applications' => $intimation_data->where('application_status', 4)->count(),
            'transfer_in_applications' => $intimation_data->where('application_status', 5)->count(),
            'transfer_out_applications' => $intimation_data->where('application_status', 6)->count(),
            'rejected_applications' => $intimation_data->where('application_status', 0)->count(),
        ];

        $lower_court_data = LowerCourt::where('is_excel', 0)->where('is_final_submitted', 1);
        $lower_court = [
            'total_applications' => $lower_court_data->count(),
            'completed_applications' => $lower_court_data->where('app_status', 1)->count(),
            'in_progress_applications' => $lower_court_data->where('app_status', 7)->count(),
            'suspended_applications' => $lower_court_data->where('app_status', 2)->count(),
            'died_applications' => $lower_court_data->where('app_status', 3)->count(),
            'removed_applications' => $lower_court_data->where('app_status', 4)->count(),
            'transfer_in_applications' => $lower_court_data->where('app_status', 5)->count(),
            'transfer_out_applications' => $lower_court_data->where('app_status', 6)->count(),
            'rejected_applications' => $lower_court_data->where('app_status', 0)->count(),
        ];


        $gc_users_data = User::query();
        $gc_users = [
            'gc_approved_users' => $gc_users_data->where('gc_approved_by', Auth::guard('admin')->user()->id)->where('gc_status', 'approved')->count(),
            'gc_disapproved_users' => $gc_users_data->where('gc_approved_by', Auth::guard('admin')->user()->id)->where('gc_status', 'disapproved')->count(),
        ];

        $my_applications = [
            'intimation_applications' => $intimation_data->where('submitted_by', Auth::guard('admin')->user()->id)->count(),
            'lower_court_applications' => $lower_court_data->where('created_by', Auth::guard('admin')->user()->id)->count(),
        ];

        return view('admin.dashboard', compact('intimation', 'lower_court', 'my_applications', 'gc_users'));
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
