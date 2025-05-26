<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GcUserController extends Controller
{
    public function index()
    {
        return view('admin.users.gc-index');
    }

    public function show($user_id)
    {
        return view('admin.users.gc-show', [
            'user_id' => $user_id
        ]);
    }
}
