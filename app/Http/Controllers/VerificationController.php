<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function create()
    {
        return view('frontend.verifications.create');
    }
}
