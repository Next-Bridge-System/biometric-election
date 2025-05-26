<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bar;
use App\District;
use App\Tehsil;
use Validator;

class BarController extends Controller
{
    public function index()
    {
        $bars = Bar::orderBy('id', 'desc')->get();
        $districts = District::orderBy('id', 'desc')->get();
        $tehsils = Tehsil::orderBy('id', 'desc')->get();

        return view('admin.bars.index', compact('bars', 'districts', 'tehsils'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'district_id' => 'required|integer',
            'tehsil_id' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = [
            'name' => $request->input('name'),
            'district_id' => $request->input('district_id'),
            'tehsil_id' => $request->input('tehsil_id'),
        ];

        $bars = Bar::create($data);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'district_id' => 'required|integer',
            'tehsil_id' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = [
            'name' => $request->input('name'),
            'district_id' => $request->input('district_id'),
            'tehsil_id' => $request->input('tehsil_id'),
        ];

        $bars->update($data);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function destroy($id)
    {
        $bar = Bar::find($id)->delete();
        return redirect()->route('bars.index');
    }
}
