<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index($slug)
    {
        return view('admin.users.index', compact('slug'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'father_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'unique:users', 'regex:/(3)[0-9]{9}/'],
            'cnic_no' => ['required', 'min:15', 'unique:users,cnic_no'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $request->merge(['clean_cnic_no' => str_replace('-', '', $request->cnic_no)]);
        $user = User::create($request->all());

        return response()->json([
            'status' => 1,
            'message' => 'User created successfully',
            'user' => $user,
        ], 200);
    }
}
