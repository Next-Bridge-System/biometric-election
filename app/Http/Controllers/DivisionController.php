<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Division;
use Validator;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::orderBy('id','desc')->get();
        return view('admin.divisions.index',compact('divisions'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'code' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = [
            'name' => $request->input('name'),
            'code' => $request->input('code'),
        ];

        $division = Division::create($data);

        return response()->json([ 'status' => 1, 'message' => 'success']);
    }

    public function update(Request $request)
    {
        $division = Division::find($request->division_id);

        $rules = [
            'name' => 'required|string|max:255',
            'code' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = [
            'name' => $request->input('name'),
            'code' => $request->input('code'),
        ];

        $division->update($data);

        return response()->json([ 'status' => 1, 'message' => 'success']);
    }

    public function destroy($id)
    {
        $division = Division::find($id)->delete();
        return redirect()->route('divisions.index');
    }

}
