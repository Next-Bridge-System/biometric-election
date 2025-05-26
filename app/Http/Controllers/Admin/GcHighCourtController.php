<?php

namespace App\Http\Controllers\Admin;

use App\AppStatus;
use App\AppType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bar;
use App\GcHighCourt;
use App\Note;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class GcHighCourtController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $application = GcHighCourt::orderBy('id', 'desc');

            return Datatables::of($application)
                ->addIndexColumn()
                ->addColumn('action', function ($application) {
                    $btn = '<a href="' . route('gc-high-court.show', $application->id) . '">
                    <span class="badge badge-primary"><i class="fas fa-eye mr-1" aria-hidden="true"></i>View</span></a>';

                    if (permission('gc_high_court_edit')) {
                        $btn .= '<a href="' . route('gc-high-court.edit', $application->id) . '">
                    <span class="badge badge-primary mr-1"><i class="fas fa-edit mr-1" aria-hidden="true"></i>Edit</span></a>';
                    }

                    $btn .= '<a href="' . route('gc-high-court.audit', $application->id) . '"><span class="badge badge-primary mr-1 mb-1"><i class="fas fa-folder mr-1" aria-hidden="true"></i>Audit</span></a>';

                    return $btn;
                })
                ->addColumn('app_status', function ($application) {
                    return appStatus($application->app_status, $application->app_type);
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->where(function ($query) use ($request) {
                            $search = $request->get('search');
                            $query->where('lawyer_name', 'LIKE', "%$search%");
                            $query->orWhere('hcr_no_hc', 'LIKE', "%$search%");
                        });
                    }
                })
                ->setRowClass(function ($row) {
                    $class = '';
                    if ($row->app_status == 8) {
                        $class = 'bg-light-green';
                    }
                    return $class;
                })
                ->rawColumns(['app_status', 'action'])
                ->make(true);
        }

        return view('admin.gc-high-court.index');
    }

    public function edit($id)
    {
        $application = GcHighCourt::findOrFail($id);
        $bars = Bar::orderBy('name', 'asc')->get();
        $user = User::where('sr_no_hc', $application->sr_no_hc)->first();
        $app_status = AppStatus::hcStatus()->get();
        $app_types = AppType::where('key', 2)->hcType()->get();

        return view('admin.gc-high-court.edit', compact('application', 'bars', 'user', 'app_status', 'app_types'));
    }

    public function update(Request $request, $id)
    {
        $application = GcHighCourt::findOrFail($id);

        $rules = [
            'lawyer_name' => 'required',
            'father_name' => 'required',
            'hcr_no_hc' => 'required',
            'license_no_hc' => 'required',
            'bf_no_hc' => 'nullable',
            'enr_date_hc' => 'required',
            'date_of_birth' => 'required',
            'age' => 'nullable',
            'cnic_no' => 'required',
            'gender' => 'required',
            'address_1' => 'required',
            'address_2' => 'nullable',
            'contact_no' => 'nullable',
            'email' => 'required',
            'enr_status_reason' => 'nullable',
            'voter_member_hc' => 'required',
            'app_status' => 'required',
            'religion' => 'required',

            'enr_date_lc' => 'required',
            'lc_ledger' => 'nullable',
            'app_type' => 'required',
            'lc_sdw' => 'required',
            'lc_lic' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = [
            'lawyer_name' => $request->lawyer_name,
            'father_name' => $request->father_name,
            'hcr_no_hc' => $request->hcr_no_hc,
            'license_no_hc' => $request->license_no_hc,
            'bf_no_hc' => $request->bf_no_hc,
            'enr_date_hc' => setDateFormat($request->enr_date_hc),
            'date_of_birth' => setDateFormat($request->date_of_birth),
            'age' => $request->age,
            'cnic_no' => $request->cnic_no,
            'gender' => $request->gender,
            'address_1' => $request->address_1,
            'address_2' => $request->address_2,
            'contact_no' => $request->contact_no,
            'email' => $request->email,
            'enr_status_reason' => $request->enr_status_reason,
            'voter_member_hc' => $request->voter_member_hc,
            'app_status' => $request->app_status,
            'religion' => $request->religion,

            'enr_date_lc' => setDateFormat($request->enr_date_lc),
            'lc_ledger' => $request->lc_ledger,
            'app_type' => $request->app_type,
            'lc_sdw' => $request->lc_sdw,
            'lc_lic' => $request->lc_lic,
        ];

        $application->update($data);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function show($id)
    {
        $application = GcHighCourt::findOrFail($id);
        $bars = Bar::orderBy('name', 'asc')->get();
        $user = User::where('sr_no_hc', $application->sr_no_hc)->first();
        $app_status = AppStatus::hcStatus()->get();
        $app_types = AppType::hcType()->get();
        $notes = Note::where('application_id',$id)->where('application_type','GC_HC')->get();

        return view('admin.gc-high-court.show', compact('application', 'bars', 'user', 'app_status', 'app_types','notes'));
    }

    public function audit($id)
    {
        $application = GcHighCourt::find($id);
        $audits = $application->audits()->paginate(10);
        return view('admin.audits.index', compact('audits'));
    }

    public function notes(Request $request)
    {
        $application = GcHighCourt::where('id', $request->gc_high_court_id)->first();

        $rules = [
            'notes' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        Note::create([
            'application_id' => $application->id,
            'application_type' => 'GC_HC',
            'note' => $request->notes,
            'admin_id' => Auth::guard('admin')->user()->id,
        ]);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function uploadMedia(Request $request){

        $request->validate([
            'profile_image' => 'required|image|max:500',
            'user_id' => 'required',
        ]);

        $user = User::find($request->user_id);

        $user->addMedia($request->profile_image)->toMediaCollection('gc_profile_image');
    }

    public function deleteMedia(Request $request){

        $request->validate([
            'user_id' => 'required',
        ]);

        $user = User::find($request->user_id);

        $mediaID = $user->getFirstMedia('gc_profile_image')->id;

        $user->deleteMedia($mediaID);

        return response()->json(['status' => 1,'message' =>'success']);
    }
}
