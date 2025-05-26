<?php

namespace App\Http\Controllers;

use App\LawyerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorHTML;
use setasign\Fpdi\Fpdi;

class SignatureController extends Controller
{
    public function index()
    {
        return view('pages.signature');
    }

    public function upload(Request $request)
    {
        $image_parts = explode(";base64,", $request->signed);
        $image_base64 = base64_decode($image_parts[1]);
        Storage::put('file.jpg', $image_base64);
        return back()->with('success', 'success Full upload signature');
    }
}
