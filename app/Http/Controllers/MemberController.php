<?php

namespace App\Http\Controllers;

use App\Member;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use App\Bar;
use App\District;
use App\Division;
use App\Tehsil;
use Illuminate\Support\Facades\Storage;
use Validator;
use Yajra\DataTables\DataTables;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // if ($request->ajax()) {
        //     $members = Member::select('*');
        //     $admin = \Auth::guard('admin')->user();

        //     return Datatables::of($members)
        //         ->addIndexColumn()
        //         ->addColumn('division_id', function (Member $data) {
        //             return getDivisionName($data->division_id);
        //         })
        //         ->addColumn('district_id', function (Member $data) {
        //             return getDistrictName($data->district_id);
        //         })
        //         ->addColumn('tehsil_id', function (Member $data) {
        //             return getTehsilName($data->tehsil_id);
        //         })
        //         ->addColumn('bar_id', function (Member $data) {
        //             return getBarName($data->bar_id);
        //         })
        //         ->addColumn('action', function (Member $data) use ($admin) {
        //             if ($admin->hasPermission('manage-members')) {
        //                 $btn =  '<a href="' . route('members.edit', $data->id) . '"><span class="btn btn-primary btn-xs mr-1"><i class="fas fa-edit mr-1" aria-hidden="true"></i>Edit</span></a>';
        //             }

        //             $btn .= '<a href="' . route('members.show', $data->id) . '"><span class="btn btn-primary btn-xs mr-1"><i class="fas fa-eye mr-1" aria-hidden="true"></i>View</span></a>';

        //             if ($admin->hasPermission('delete-members')) {
        //                 $btn .= '<button type="button" class="btn btn-danger btn-xs mr-1" onclick="deleteMember(' . $data->id . ')"><i class="fas fa-info-circle mr-1"></i>Delete</button>';
        //             }

        //             return $btn;
        //         })
        //         ->rawColumns(['action'])
        //         ->make(true);
        // }

        return view('admin.members.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $response['divisions'] = Division::orderBy('name', 'asc')->get();
        return view('admin.members.create')->with($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'father_name' => 'nullable',
            'committee_name' => 'nullable',
            'mobile_no' => 'required',
            'division_id' => 'nullable',
            'district_id' => 'nullable',
            'tehsil_id' => 'nullable',
            'bar_id' => 'nullable',
            'address' => 'required',
            'tenure_start_date' => 'required',
            'tenure_end_date' => 'required',
            'designation' => 'required',
            'signature_url' => 'required|mimes:jpg,jpeg,png',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $startDate = \DateTime::createFromFormat('d-m-Y', $request->tenure_start_date);
        $endDate = \DateTime::createFromFormat('d-m-Y', $request->tenure_end_date);
        $exsistingMember = Member::where('designation',$request->designation)
            ->whereDate('tenure_start_date','<=',$startDate)
            ->whereDate('tenure_end_date','>=',$startDate)
            ->get();

        if(strtotime($startDate->format('Y-m-d')) > strtotime($endDate->format('Y-m-d'))){
            return response()->json([
                'status' => 0,
                'message' => 'The End Date should be ahead of Start Date',
            ]);
        }elseif($exsistingMember->count() > 0){
            return response()->json([
                'status' => 0,
                'message' => 'There is exsiting member within the tenure',
            ]);
        }



        $member = Member::create([
            'name' => $request->name,
            'father_name' => $request->father_name,
            'committee_name' => $request->committee_name,
            'mobile_no' => $request->mobile_no,
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'tehsil_id' => $request->tehsil_id,
            'bar_id' => $request->bar_id,
            'address' => $request->address,
            'tenure_start_date' => $startDate,
            'tenure_end_date' => $endDate,
            'designation' => $request->designation,
        ]);

        $this->uploadMemberImage($request,$member->id);

        return response()->json([
            'status' => 1,
            'message' => "Record Added Successfully",
            'redirect_url' => route('members.index'),
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response['member'] = Member::find($id);

        if ($response['member'] ==  null) {
            return back()->with('error', 'No Record Found');
        }
        return view('admin.members.show')->with($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response['member'] = Member::find($id);

        if ($response['member'] ==  null) {
            return back()->with('error', 'No Record Found');
        }

        $response['divisions'] = Division::orderBy('name', 'asc')->get();
        $response['districts'] = District::where('division_id', $response['member']->division_id)->get();
        $response['tehsils'] = Tehsil::where('district_id', $response['member']->district_id)->get();
        $response['bars'] = Bar::where('tehsil_id', $response['member']->tehsil_id)->get();
        return view('admin.members.edit')->with($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $member = Member::find($id);

        if ($member ==  null) {
            return response()->json([
                'error' => 'No Record Found',
            ], 422);
        }

        $rules = [
            'name' => 'required',
            'father_name' => 'nullable',
            'committee_name' => 'nullable',
            'mobile_no' => 'required',
            'division_id' => 'nullable',
            'district_id' => 'nullable',
            'tehsil_id' => 'nullable',
            'bar_id' => 'nullable',
            'address' => 'required',
            'tenure_start_date' => 'required',
            'tenure_end_date' => 'required',
            'designation' => 'required',
            'signature_url' => 'nullable|mimes:jpg,jpeg,png',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $startDate = \DateTime::createFromFormat('d-m-Y', $request->tenure_start_date);
        $endDate = \DateTime::createFromFormat('d-m-Y', $request->tenure_end_date);
        $exsistingMember = Member::where('id','!=',$member->id)
            ->where('designation',$request->designation)
            ->whereDate('tenure_start_date','<=',$startDate)
            ->whereDate('tenure_end_date','>=',$startDate)
            ->get();

        if(strtotime($startDate->format('Y-m-d')) > strtotime($endDate->format('Y-m-d'))){
            return response()->json([
                'status' => 0,
                'message' => 'The End Date should be ahead of Start Date',
            ]);
        }elseif($exsistingMember->count() > 0){
            return response()->json([
                'status' => 0,
                'message' => 'There is exsiting member within the tenure',
            ]);
        }

        $member->update([
            'name' => $request->name,
            'father_name' => $request->father_name,
            'committee_name' => $request->committee_name,
            'mobile_no' => $request->mobile_no,
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'tehsil_id' => $request->tehsil_id,
            'bar_id' => $request->bar_id,
            'address' => $request->address,
            'tenure_start_date' => $startDate,
            'tenure_end_date' => $endDate,
            'designation' => $request->designation,
        ]);

        if($request->has('signature_url')){
            $this->uploadMemberImage($request,$member->id);
        }

        return response()->json([
            'status' => 1,
            'message' => "Record Updated Successfully",
            'redirect_url' => route('members.index'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $member = Member::find($request->id);
        $member->delete();

        return response()->json([
            'status' => 1,
            'message' => "Record Updated Successfully",
        ]);
    }

    public function uploadMemberImage(Request $request, $id)
    {
        $model = Member::find($id);
        $directory = 'members/'.$model->id;
        if ($request->hasFile('signature_url')) {
            $fileName = $request->file('signature_url')->getClientOriginalName();
            if(!Storage::exists($directory)){
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('signature_url')));
            $model->update(['signature_url'=> $url]);
        }
    }
}
