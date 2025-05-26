<?php

namespace App\Http\Controllers\Admin;

use App\AppStatus;
use App\Bar;
use App\GcHighCourt;
use App\GcLowerCourt;
use App\Http\Controllers\Controller;
use App\Note;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class GcLowerCourtController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $application = GcLowerCourt::orderBy('id', 'desc');

            return Datatables::of($application)
                ->addIndexColumn()
                ->addColumn('action', function ($application) {
                    $btn = '<a href="' . route('gc-lower-court.show', $application->id) . '">
                    <span class="badge badge-primary mr-1 mb-1"><i class="fas fa-eye mr-1" aria-hidden="true"></i>View</span></a>';
                    if (permission('gc_lower_court_edit')) {
                        $btn .= '<a href="' . route('gc-lower-court.edit', $application->id) . '"><span class="badge badge-primary mr-1 mb-1"><i class="fas fa-edit mr-1" aria-hidden="true"></i>Edit</span></a>';
                    }

                    $btn .= '<a href="' . route('gc-lower-court.audit', $application->id) . '"><span class="badge badge-primary mr-1 mb-1"><i class="fas fa-folder mr-1" aria-hidden="true"></i>Audit</span></a>';

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
                            $query->orWhere('reg_no_lc', 'LIKE', "%$search%");
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

        return view('admin.gc-lower-court.index');
    }

    public function edit($id)
    {
        $application = GcLowerCourt::findOrFail($id);
        $bars = Bar::orderBy('name', 'asc')->get();
        $user = User::where('sr_no_lc', $application->sr_no_lc)->first();
        $app_status = AppStatus::lcStatus()->get();

        return view('admin.gc-lower-court.edit', compact('application', 'bars', 'user', 'app_status'));
    }

    public function update(Request $request, $id)
    {
        $application = GcLowerCourt::findOrFail($id);

        $rules = [
            'reg_no_lc' => 'required',
            'license_no_lc' => 'required',
            'bf_no_lc' => 'nullable',
            'lawyer_name' => 'required',
            'father_name' => 'required',
            'address_1' => 'required',
            'address_2' => 'nullable',
            'religion' => 'nullable',
            'cnic_no' => 'nullable',
            'contact_no' => 'nullable',
            'email' => 'nullable',
            'app_status' => 'required',
            'date_of_enrollment_lc' => 'required',
            'date_of_birth' => 'required',
            'voter_member_lc' => 'required',
            'enr_status_reason' => 'required',
            'enr_app_sdw' => 'required',
            'gender' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = [
            'reg_no_lc' => $request->reg_no_lc,
            'license_no_lc' => $request->license_no_lc,
            'bf_no_lc' => $request->bf_no_lc,
            'lawyer_name' => $request->lawyer_name,
            'father_name' => $request->father_name,
            'address_1' => $request->address_1,
            'address_2' => $request->address_2,
            'religion' => $request->religion,
            'cnic_no' => $request->cnic_no,
            'contact_no' => $request->contact_no,
            'email' => $request->email,
            'app_status' => $request->app_status,
            'date_of_enrollment_lc' => setDateFormat($request->date_of_enrollment_lc),
            'date_of_birth' => setDateFormat($request->date_of_birth),
            'voter_member_lc' => $request->voter_member_lc,
            'enr_status_reason' => $request->enr_status_reason,
            'enr_app_sdw' => $request->enr_app_sdw,
            'gender' => $request->gender,
        ];

        $application->update($data);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function show($id)
    {
        $application = GcLowerCourt::findOrFail($id);
        $bars = Bar::orderBy('name', 'asc')->get();
        $user = User::where('sr_no_lc', $application->sr_no_lc)->first();
        $app_status = AppStatus::lcStatus()->get();
        $notes = Note::where('application_id', $id)->where('application_type', 'GC_LC')->get();

        return view('admin.gc-lower-court.show', compact('application', 'bars', 'user', 'app_status', 'notes'));
    }

    public function audit($id)
    {
        $application = GcLowerCourt::find($id);
        $audits = $application->audits()->paginate(10);
        return view('admin.audits.index', compact('audits'));
    }

    public function notes(Request $request)
    {
        $application = GcLowerCourt::where('id', $request->gc_lower_court_id)->first();

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
            'application_type' => 'GC_LC',
            'note' => $request->notes,
            'admin_id' => Auth::guard('admin')->user()->id,
        ]);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function uploadMedia(Request $request)
    {

        $request->validate([
            'profile_image' => 'required|image|max:500',
            'user_id' => 'required',
        ]);

        $user = User::find($request->user_id);

        $user->addMedia($request->profile_image)->toMediaCollection('gc_profile_image');
    }

    public function deleteMedia(Request $request)
    {

        $request->validate([
            'user_id' => 'required',
        ]);

        $user = User::find($request->user_id);

        $mediaID = $user->getFirstMedia('gc_profile_image')->id;

        $user->deleteMedia($mediaID);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function moveToHC(Request $request)
    {
        try {

            DB::beginTransaction();

            $gc_lc = GcLowerCourt::find($request->application_id);
            $gc_hc = GcHighCourt::orderBy('sr_no_hc', 'desc')->first();
            $last_sr_no_hc = $gc_hc->sr_no_hc + 1;

            $status = 1;
            $message = "Application moved to GC High Court";

            if ($gc_lc) {
                if ($gc_lc->app_type != 4) {
                    $gc_hc_data = [
                        "user_id" => $gc_lc->user_id,
                        "sr_no_hc" => $last_sr_no_hc,
                        "lawyer_name" => $gc_lc->lawyer_name,
                        "father_name" => $gc_lc->father_name,
                        "hcr_no_hc" => NULL,
                        "license_no_hc" => NULL,
                        "bf_no_hc" => $gc_lc->bf_no_lc,
                        "enr_date_hc" => NULL,
                        "date_of_birth" => $gc_lc->date_of_birth,
                        "age" => $gc_lc->age,
                        "cnic_no" => $gc_lc->cnic_no,
                        "gender" => $gc_lc->gender,
                        "address_1" => $gc_lc->address_1,
                        "address_2" => $gc_lc->address_2,
                        "enr_date_lc" => $gc_lc->date_of_enrollment_lc,
                        "contact_no" => $gc_lc->contact_no,
                        "email" => $gc_lc->email,
                        "enr_status_type" => NULL,
                        "enr_status_reason" => $gc_lc->enr_status_reason,
                        "lc_ledger" => $gc_lc->reg_no_lc,
                        "lc_sdw" => $gc_lc->enr_app_sdw,
                        "lc_last_status" => NULL,
                        "voter_member_hc" => $gc_lc->voter_member_lc,
                        "image" => $gc_lc->image,
                        "lc_lic" => $gc_lc->license_no_lc,
                        "app_status" => $gc_lc->app_status,
                        "app_type" => 6,
                        "created_by" => auth()->guard('admin')->id(),
                        "gc_created_by" => $gc_lc->gc_created_by,
                        "gc_updated_by" => $gc_lc->gc_updated_by,
                        "religion" => $gc_lc->religion,
                        "rcpt_no_hc" => NULL,
                        "rcpt_date" => NULL,
                    ];

                    $new_gc_hc = GcHighCourt::create($gc_hc_data);

                    $gc_lc->update([
                        'move_to_hc' => true,
                        'move_to_hc_at' => Carbon::now(),
                        'app_type' => 4,
                    ]);

                    $user = User::where('id', $gc_lc->user_id)->first();
                    if ($user) {
                        $user->update([
                            'sr_no_hc' => $new_gc_hc->sr_no_hc,
                            'register_as' => 'gc_hc',
                        ]);
                    }

                    // COPY IMG FROM GCLC TO GCHC
                    $sourcePath = 'applications/profile-images/LC/' . $gc_lc->image;
                    $destinationPath = 'applications/profile-images/HC/' . $gc_lc->image;

                    if (Storage::exists($sourcePath)) {
                        Storage::copy($sourcePath, $destinationPath);
                    }

                } else {
                    $status = 0;
                    $message = "Application already in High Court";
                }
            } else {
                $status = 0;
                $message = "Application not found";
            }

            DB::commit();
            return response()->json(['status' => $status, 'message' => $message]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
