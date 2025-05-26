<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\University;
use App\Country;
use App\Province;
use App\District;
use Validator;
use Illuminate\Validation\Rule;

class UniversityController extends Controller
{
    public function index()
    {
        $universities= University::orderBy('id','desc')->where('approved', 1)->get();
        return view('admin.universities.index',compact('universities'));
    }

    public function create()
    {
        $countries= Country::orderBy('name','asc')->get();
        $provinces= Province::orderBy('name','asc')->get();
        $districts= District::orderBy('name','asc')->get();
        $universities= University::select('id','name')->where('type', 1)->orderBy('id','desc')->get();

        return view('admin.universities.create',compact('countries','districts','provinces','universities'));
    }

    public function store(Request $request)
    {
        $rules = [
            'type' => 'required|integer|in:1,2',
            'aff_university_id' => ['nullable','integer',Rule::requiredIf($request->type == '2')],
            'name' => 'required|string|max:255',
            // 'university_phone' => 'required',
            'email' => 'required|string|email|max:255',
            // 'country_id' => 'required|string|max:255',
            // 'province_id' => ['nullable','integer',Rule::requiredIf($request->country_id == '166')],
            // 'district_id' => ['nullable','integer',Rule::requiredIf($request->country_id == '166')],
            // 'city' => 'required|string|max:255',
            // 'address' => 'required|string|max:255',
            // 'postal_code' => 'required|numeric|digits:5',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = [
            'type' => $request->input('type'),
            'aff_university_id' => $request->input('aff_university_id'),
            'name' => $request->input('name'),
            'phone' => $request->input('university_phone'),
            'email' => $request->input('email'),
            'country_id' => $request->input('country_id'),
            'province_id' => $request->input('province_id'),
            'district_id' => $request->input('district_id'),
            'city' => $request->input('city'),
            'address' => $request->input('address'),
            'postal_code' => $request->input('postal_code'),
        ];

        $university = University::create($data);

        return response()->json([ 'status' => 1, 'message' => 'success']);
    }

    public function edit($id)
    {
        $university = University::find($id);
        $countries= Country::orderBy('name','asc')->get();
        $provinces= Province::orderBy('name','asc')->get();
        $districts= District::orderBy('name','asc')->get();
        $universities= University::select('id','name')->where('type' , 1)->orderBy('id','desc')->get();

        return view('admin.universities.edit',compact('university','countries','districts','provinces','universities'));
    }

    public function update(Request $request, $id)
    {
        $university = University::find($id);

        $rules = [
            'type' => 'required|integer|in:1,2',
            'aff_university_id' => ['nullable','integer',Rule::requiredIf($request->type == '2')],
            'name' => 'required|string|max:255',
            // 'university_phone' => 'required',
            'email' => 'required|string|email|max:255',
            // 'country_id' => 'required|string|max:255',
            // 'province_id' => ['nullable','integer',Rule::requiredIf($request->country_id == '166')],
            // 'district_id' => ['nullable','integer',Rule::requiredIf($request->country_id == '166')],
            // 'city' => 'required|string|max:255',
            // 'address' => 'required|string|max:255',
            // 'postal_code' => 'required|numeric|digits:5',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = [
            'type' => $request->input('type'),
            'aff_university_id' => $request->input('aff_university_id'),
            'name' => $request->input('name'),
            'phone' => $request->input('university_phone'),
            'email' => $request->input('email'),
            'country_id' => $request->input('country_id'),
            'province_id' => $request->input('province_id'),
            'district_id' => $request->input('district_id'),
            'city' => $request->input('city'),
            'address' => $request->input('address'),
            'postal_code' => $request->input('postal_code'),
            'approved' => $request->input('approved'),
        ];

        $university->update($data);

        return response()->json([ 'status' => 1, 'message' => 'success']);
    }

    public function show($id)
    {
        $university = University::find($id);
        return view('admin.universities.show',compact('university'));
    }

    public function destroy($id)
    {
        $university = University::find($id)->delete();
        return redirect()->route('universities.index');
    }

    public function unapproved()
    {
        $unapprovedUniversities= University::orderBy('id','desc')->where('approved', 0)->get();
        return view('admin.universities.unapproved',compact('unapprovedUniversities'));
    }
}
