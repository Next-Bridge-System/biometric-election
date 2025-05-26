<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        return view('admin.complaint.index');
    }

    public function show($id)
    {
        return view('admin.complaint.show', [
            'complaint_id' => $id
        ]);
    }
}
