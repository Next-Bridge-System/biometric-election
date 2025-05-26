<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Division;
use App\District;
use App\Tehsil;
use Validator;

class DistrictController extends Controller
{
    public function index()
    {
        $districts = District::orderBy('id','desc')->get();
        return view('admin.districts.index',compact('districts'));
    }

    public function create()
    {
        $divisions = Division::select('id','name','code')->get();
        return view ('admin.districts.create',compact('divisions'));
    }

    public function store(Request $request)
    {
        $rules = [
            'division_id' => 'required|integer',
            'district_name' => 'required|string|max:255',
            'district_code' => 'required|string|max:50',
            'tehsils' => 'required|array',
            'tehsils.*.tehsil_name' => 'required|string|max:255',
            'tehsils.*.tehsil_code' => 'required|string|max:50',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = [
            'division_id' => $request->input('division_id'),
            'name' => ucwords(strtolower($request->input('district_name'))),
            'code' => $request->input('district_code'),
        ];

        $district = District::create($data);

        $tehsils = $request->input('tehsils');
        $data2 = [];
        foreach ($tehsils as $key => $tehsil) {
            $data2 = [
                'district_id' => $district->id,
                'name' => ucwords(strtolower($tehsil['tehsil_name'])),
                'code' => $tehsil['tehsil_code'],
            ];
            Tehsil::create($data2);
        }

        return response()->json([ 'status' => 1, 'message' => 'success']);
    }

    public function edit($id)
    {
        $district = District::find($id);
        $divisions = Division::select('id','name','code')->get();
        return view('admin.districts.edit',compact('district','divisions'));
    }

    public function update(Request $request, $id)
    {
        $district = District::find($id);

        $rules = [
            'district_name' => 'required|string|max:255',
            'district_code' => 'required|string|max:50',
            'tehsils' => 'nullable|array',
            'tehsils.*.tehsil_name' => 'required|string|max:255',
            'tehsils.*.tehsil_code' => 'required|string|max:50',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = [
            'division_id' => $request->input('division_id'),
            'name' => ucwords(strtolower($request->input('district_name'))),
            'code' => $request->input('district_code'),
        ];

        $district->update($data);

        return response()->json([ 'status' => 1, 'message' => 'success']);
    }

    public function destroy($id)
    {
        $district = District::find($id)->delete();
        return redirect()->route('districts.index');
    }

    public function storeTehsil(Request $request, $id)
    {
        $rules = [
            'tehsil_name' => 'required|string|max:255',
            'tehsil_code' => 'required|string|max:50',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = [
            'district_id' => $id,
            'name' => ucwords(strtolower($request->input('tehsil_name'))),
            'code' => $request->input('tehsil_code'),
        ];

        $tehsil = Tehsil::create($data);

        return response()->json([ 'status' => 1, 'message' => 'success']);
    }

    public function updateTehsil(Request $request)
    {
        $tehsil = Tehsil::find($request->tehsil_id);

        $rules = [
            'tehsil_name' => 'required|string|max:255',
            'tehsil_code' => 'required|string|max:50',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = [
            'name' => ucwords(strtolower($request->input('tehsil_name'))),
            'code' => $request->input('tehsil_code'),
        ];

        $tehsil->update($data);

        return response()->json([ 'status' => 1, 'message' => 'success']);
    }

    public function destroyTehsil($id)
    {
        $tehsil = Tehsil::find($id)->delete();
        return redirect()->back();
    }
}
