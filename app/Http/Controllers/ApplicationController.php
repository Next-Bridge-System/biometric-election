<?php

namespace App\Http\Controllers;

use App\Exports\VPApplicationExport;
use App\PrintSecureCard;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ApplicationsExport;
use App\Imports\ApplicationImport;

use App\Application;
use App\District;
use App\Tehsil;
use App\LawyerUpload;
use App\Admin;
use App\PrintCertificate;

use Carbon\Carbon;
use Validator;
use Log;
use PDF;
use DB;
use Auth;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $applications = Application::select('*')->whereIn('application_type', ['1', '2', '3', '4'])->orderBy('id', 'DESC')->where('is_approved', 1)->get();
        $operators = Admin::select('id', 'name')->get();
        $printedCertificates = PrintCertificate::select('id')->where('is_processed', 0)->get();

        if ($request->ajax()) {
            $application = Application::select('*')->whereIn('application_type', ['1', '2', '3', '4'])->where('is_approved', 1);
            $admin = Auth::guard('admin')->user();

            return Datatables::of($application)
                ->addIndexColumn()
                ->addColumn('checkbox', function (Application $application) {
                    $checkbox = '<input type="checkbox" class="application_checked" name="application_token_no[]" value="' . $application->id . '"/>';
                    return $checkbox;
                })
                ->addColumn('application_type', function (Application $application) {
                    if ($application->application_type == 1) $applicationType = 'Lower Court';
                    else if ($application->application_type == 2) $applicationType = 'High Court';
                    else if ($application->application_type == 3) $applicationType = 'Renewal High Court';
                    else if ($application->application_type == 4) $applicationType = 'Renewal Low Court';
                    return $applicationType;
                })
                ->addColumn('active_mobile_no', function (Application $application) {
                    $activeMobileNumber = "+92" . $application->active_mobile_no;
                    return $activeMobileNumber;
                })
                ->addColumn('application_status', function (Application $application) {
                    if ($application->application_status == 1) $applicationStatus = 'Active';
                    else if ($application->application_status == 2) $applicationStatus = 'Suspended';
                    else if ($application->application_status == 3) $applicationStatus = 'Died';
                    else if ($application->application_status == 4) $applicationStatus = 'Removed';
                    else if ($application->application_status == 5) $applicationStatus = 'Transfer in';
                    else if ($application->application_status == 6) $applicationStatus = 'Transfer out';
                    return $applicationStatus;
                })
                ->addColumn('card_status', function (Application $application) {
                    if ($application->card_status == 1)
                        $cardStatus = '<span class="badge badge-warning">Pending</span>';
                    else if ($application->card_status == 2)
                        $cardStatus = '<span class="badge badge-primary">Printing</span>';
                    else if ($application->card_status == 3)
                        $cardStatus = '<span class="badge badge-success">Dispatched</span>';
                    else if ($application->card_status == 4)
                        $cardStatus = '<span class="badge badge-success">By Hand</span>';
                    else if ($application->card_status == 5)
                        $cardStatus = '<span class="badge badge-success">Done</span>';
                    return $cardStatus;
                })
                ->addColumn('address', function (Application $application) {
                    $address = $application->postal_address . ' ' . $application->address_2;
                    return $address;
                })
                ->addColumn('submitted_by', function (Application $application) {
                    if (isset($application->submitted_by)) {
                        $submittedBy = getAdminName($application->submitted_by);
                    } else {
                        $submittedBy = "Online User";
                    }
                    return $submittedBy;
                })
                ->addColumn('action', function (Application $application) {

                    $btn = '<a href="' . route('applications.show', $application->id) . '">
                    <span class="badge badge-primary mr-1 mr-1"><i class="fas fa-eye mr-1" aria-hidden="true"></i>View</span></a>';

                    if (Auth::guard('admin')->user()->hasPermission('edit-applications')) {
                        $btn .= '<a href="' . route('applications.edit', $application->id) . '"><span class="badge badge-primary mr-1"><i class="far fa-edit mr-1" aria-hidden="true"></i>Edit</span></a>';
                    }

                    if (Auth::guard('admin')->user()->hasPermission('delete-applications')) {
                        $btn .= ' <a href="javascript:void(0)" data-action="' . route('applications.destroy', $application->id) . '" onclick="deleteApplication(this)"><span class="badge badge-danger mr-1"><i class="fas fa-trash-alt mr-1" aria-hidden="true"></i>Delete</span></a>';
                    }

                    $btn .= '<a href="' . route('applications.pdf-view', ['download' => 'pdf', 'application' => $application]) . '">
                    <span class="badge badge-success mr-1"><i class="fas fa-download mr-1" aria-hidden="true"></i>Download PDF</span></a>';

                    // if (isset($application->printCertificate) && $application->printCertificate != null) {
                    //     $btn .= '<div class="printCertificateSection d-inline-flex"><a href="javascript:void(0)">
                    //     <span class="badge badge-success mr-1"><i class="fas fa-check mr-1" aria-hidden="true"></i> Certificate Printed</span></a></div>';
                    // } else {
                    $btn .= '<div class="printCertificateSection d-inline-flex"><a class="certificatePrint" href="javascript:void(0)" data-action="' . route('applications.certificate', ['download' => 'pdf', 'application' => $application]) . '">
                        <span class="badge badge-success mr-1"><i class="fas fa-print mr-1" aria-hidden="true"></i>Print Certificate</span></a></div>';
                    // }

                    $btn .= '<a href="' . route('applications.exportPDFs', ['download' => 'pdf', 'ids' => $application->id]) . '" target="_blank">
                    <span class="badge badge-success mr-1"><i class="fas fa-print mr-1" aria-hidden="true"></i>Print VP Letter</span></a>';

                    $btn .= '<a href="' . route('applications.exportPrint', ['download' => 'pdf', 'ids' => $application->id]) . '" target="_blank">
                    <span class="badge badge-success mr-1"><i class="fas fa-print mr-1" aria-hidden="true"></i>Print VP Envelop</span></a>';

                    return $btn;
                })
                ->filter(function ($instance) use ($request) {

                    if ($request->get('application_type')) {
                        $instance->where('application_type', $request->get('application_type'));
                    }

                    if ($request->get('application_date')) {
                        if ($request->get('application_date') == '1') {
                            $instance->whereDate('created_at', Carbon::today());
                        }
                        if ($request->get('application_date') == '2') {
                            $instance->whereDate('created_at', Carbon::yesterday());
                        }
                        if ($request->get('application_date') == '3') {
                            $date = Carbon::now()->subDays(7);
                            $instance->where('created_at', '>=', $date);
                        }
                        if ($request->get('application_date') == '4') {
                            $date = Carbon::now()->subDays(30);
                            $instance->where('created_at', '>=', $date);
                        }
                        if ($request->application_date == 5) {
                            if ($request->get('application_date_range')) {
                                $dateRange = explode(' - ', $request->application_date_range);
                                $from = date("Y-m-d", strtotime($dateRange[0]));
                                $to = date("Y-m-d", strtotime($dateRange[1]));
                                $instance->whereBetween('created_at', [$from, $to]);
                            }
                        }
                    }

                    if ($request->get('application_operator')) {
                        $instance->where('submitted_by', $request->application_operator);
                    }

                    if (!empty($request->get('search'))) {
                        $instance->where(function ($query) use ($request) {
                            $search = $request->get('search');
                            $query->orWhere('advocates_name', 'LIKE', "%$search%")
                                ->orWhere('cnic_no', 'LIKE', "%$search%")
                                ->orWhere('active_mobile_no', 'LIKE', "%$search%")
                                ->orWhere('postal_address', 'LIKE', "%$search%")
                                ->orWhere('address_2', 'LIKE', "%$search%")
                                ->orWhere('application_token_no', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['checkbox', 'application_type', 'application_status', 'card_status', 'action', 'active_mobile_no', 'address', 'submitted_by'])
                ->make(true);
        }

        return view('admin.applications.index', compact('applications', 'operators', 'printedCertificates'));
    }

    public function create()
    {
        $districts = District::orderBy('name', 'asc')->get();
        $tehsils = Tehsil::orderBy('name', 'asc')->get();
        return view('admin.applications.create', compact('districts', 'tehsils'));
    }

    public function store(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $rules = [
            'application_type' => 'nullable|integer|in:1,2,3,4',
            'advocates_name' => 'nullable|max:255',
            'so_of' => 'nullable|max:255',
            // 'reg_no_lc' => ['max:255','unique:applications',Rule::requiredIf($request->application_type == 1)],
            'reg_no_lc' => 'nullable|max:255|unique:applications',
            // 'license_no_hc' => ['max:255','unique:applications',Rule::requiredIf($request->application_type == 2)],
            'license_no_lc' => 'nullable|max:255|unique:applications',
            'license_no_hc' => 'nullable|max:255|unique:applications',
            'hcr_no' => 'nullable|max:255|unique:applications',
            'high_court_roll_no' => 'nullable|max:255',
            'district_id' => 'nullable|integer',
            'tehsil_id' => 'nullable|integer',
            'date_of_birth' => 'nullable|max:255',
            'date_of_enrollment_lc' => 'nullable|max:255',
            // 'date_of_enrollment_hc' => ['max:255',Rule::requiredIf($request->application_type == 2)],
            'date_of_enrollment_hc' => 'nullable|max:255',
            'cnic_no' => 'required|unique:applications',
            'postal_address' => 'nullable|max:255',
            'email' => 'nullable|email|max:255|unique:applications',
            'whatsapp_no' => 'nullable|numeric|digits:10|unique:applications',
            'active_mobile_no' => 'nullable|numeric|digits:10|unique:applications',
            'voter_member_lc' => 'nullable|max:255',
            // 'voter_member_hc' => ['max:255',Rule::requiredIf($request->application_type == 2)],
            'voter_member_hc' => 'nullable|max:255',
            'rf_id' => 'nullable|numeric',
            // 'bf_no_hc' => ['numeric',Rule::requiredIf($request->application_type == 2)],
            'bf_no_lc' => 'nullable|numeric',
            'bf_no_hc' => 'nullable|numeric',
            'profile_image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cnic_front_image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cnic_back_image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'id_card_front_image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'id_card_back_image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = [
            'application_type' => $request->input('application_type'),
            'advocates_name' => $request->input('advocates_name'),
            'so_of' => $request->input('so_of'),
            'reg_no_lc' => $request->input('reg_no_lc'),
            'license_no_lc' => $request->input('license_no_lc'),
            'license_no_hc' => $request->input('license_no_hc'),
            'hcr_no' => $request->input('hcr_no'),
            'high_court_roll_no' => $request->input('high_court_roll_no'),
            'district_id' => $request->input('district_id'),
            'tehsil_id' => $request->input('tehsil_id'),
            'date_of_birth' => $request->input('date_of_birth'),
            'date_of_enrollment_lc' => $request->input('date_of_enrollment_lc'),
            'date_of_enrollment_hc' => $request->input('date_of_enrollment_hc'),
            'cnic_no' => $request->input('cnic_no'),
            'postal_address' => $request->input('postal_address'),
            'email' => $request->input('email'),
            'whatsapp_no' => $request->input('whatsapp_no'),
            'active_mobile_no' => $request->input('active_mobile_no'),
            'voter_member_lc' => $request->input('voter_member_lc'),
            'voter_member_hc' => $request->input('voter_member_hc'),
            'bf_no_lc' => $request->input('bf_no_lc'),
            'bf_no_hc' => $request->input('bf_no_hc'),
            'rf_id' => $request->input('rf_id'),
        ];

        $application = Application::create($data);
        $application->update([
            'application_token_no' => $application->id + 1000,
            'submitted_by' => $admin->id,
        ]);

        $this->uploadProfileImage($request, $application->id);
        $this->uploadCnicFrontImage($request, $application->id);
        $this->uploadCnicBackImage($request, $application->id);
        $this->uploadIdCardFrontImage($request, $application->id);
        $this->uploadIdCardBackImage($request, $application->id);

        return response()->json([
            'status' => 1,
            'message' => 'success',
            'application' => $application->id,
        ]);
    }

    public function show($id)
    {
        $application = Application::findOrFail($id);
        return view('admin.applications.show', compact('application'));
    }

    public function edit($id)
    {
        $application = Application::findOrFail($id);
        $districts = District::orderBy('name', 'asc')->get();
        $tehsils = Tehsil::orderBy('name', 'asc')->get();
        return view('admin.applications.edit', compact('application', 'districts', 'tehsils'));
    }

    public function update(Request $request, $id)
    {
        $application = Application::find($id);
        $admin = Auth::guard('admin')->user();

        $rules = [
            'application_status' => 'nullable|integer|in:1,2,3,4,5,6',
            'card_status' => 'nullable|integer|in:1,2,3,4,5',
            'advocates_name' => 'nullable|max:255',
            'so_of' => 'nullable|max:255',
            'reg_no_lc' => [
                // Rule::requiredIf($request->application_type == 1),
                'nullable',
                Rule::unique('applications')->ignore($application->id, 'id')
            ],
            'license_no_lc' => [
                // Rule::requiredIf($request->application_type == 2),
                'nullable',
                Rule::unique('applications')->ignore($application->id, 'id')
            ],
            'license_no_hc' => [
                // Rule::requiredIf($request->application_type == 2),
                'nullable',
                Rule::unique('applications')->ignore($application->id, 'id')
            ],
            'hcr_no' => [
                // Rule::requiredIf($request->application_type == 2),
                'nullable',
                Rule::unique('applications')->ignore($application->id, 'id')
            ],
            'high_court_roll_no' => 'nullable|max:255',
            'district_id' => 'nullable|integer',
            'tehsil_id' => 'nullable|integer',
            'date_of_birth' => 'nullable|max:255',
            'date_of_enrollment_lc' => 'nullable|max:255',
            // 'date_of_enrollment_hc' => ['max:255',Rule::requiredIf($request->application_type == 2)],
            'date_of_enrollment_hc' => 'nullable|max:255',
            'cnic_no' => 'required|unique:applications,cnic_no,' . $application->id,
            'postal_address' => 'nullable|max:255',
            'email' => 'nullable|email|max:255',
            'whatsapp_no' => 'nullable|numeric|digits:10|unique:applications,whatsapp_no,' . $application->id,
            'active_mobile_no' => 'nullable|numeric|digits:10|unique:applications,active_mobile_no,' . $application->id,
            'voter_member_lc' => 'nullable|max:255',
            // 'voter_member_hc' => ['max:255',Rule::requiredIf($request->application_type == 2)],
            'voter_member_hc' => 'nullable|max:255',
            'rf_id' => 'nullable|numeric',
            // 'bf_no_hc' => ['numeric',Rule::requiredIf($request->application_type == 2)],
            'bf_no_lc' => 'nullable|numeric',
            'bf_no_hc' => 'nullable|numeric',
            'profile_image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cnic_front_image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cnic_back_image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'id_card_front_image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'id_card_back_image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = [
            'application_status' => $request->input('application_status'),
            'card_status' => $request->input('card_status'),
            'advocates_name' => $request->input('advocates_name'),
            'so_of' => $request->input('so_of'),
            'reg_no_lc' => $request->input('reg_no_lc'),
            'license_no_lc' => $request->input('license_no_lc'),
            'license_no_hc' => $request->input('license_no_hc'),
            'hcr_no' => $request->input('hcr_no'),
            'high_court_roll_no' => $request->input('high_court_roll_no'),
            'district_id' => $request->input('district_id'),
            'tehsil_id' => $request->input('tehsil_id'),
            'date_of_birth' => $request->input('date_of_birth'),
            'date_of_enrollment_lc' => $request->input('date_of_enrollment_lc'),
            'date_of_enrollment_hc' => $request->input('date_of_enrollment_hc'),
            'cnic_no' => $request->input('cnic_no'),
            'postal_address' => $request->input('postal_address'),
            'email' => $request->input('email'),
            'whatsapp_no' => $request->input('whatsapp_no'),
            'active_mobile_no' => $request->input('active_mobile_no'),
            'voter_member_lc' => $request->input('voter_member_lc'),
            'voter_member_hc' => $request->input('voter_member_hc'),
            'bf_no_lc' => $request->input('bf_no_lc'),
            'bf_no_hc' => $request->input('bf_no_hc'),
            'rf_id' => $request->input('rf_id'),
            'updated_by' => $admin->id,
        ];

        $application->update($data);

        $this->uploadProfileImage($request, $application->id);
        $this->uploadCnicFrontImage($request, $application->id);
        $this->uploadCnicBackImage($request, $application->id);
        $this->uploadIdCardFrontImage($request, $application->id);
        $this->uploadIdCardBackImage($request, $application->id);

        //  SEND STATUS CHANGE SMS TO USERS
        if (
            array_key_exists("card_status", $application->getChanges()) &&
            ($application->card_status == 2 || $application->card_status == 3)
        ) {

            if ($application->card_status == 2) {
                $message = 'Your card is send for printing process.';
            } else if ($application->card_status == 3) {
                $message = 'Your card has been dispatched.';
            }

            $data = array(
                "phone" => '+92' . $application->active_mobile_no,
                "message" => $message,
            );

            $jsonData = json_encode($data);
            $json_url = "https://pb-staging.herokuapp.com/v1/external/send-sms";

            $ch = curl_init($json_url);
            $options = array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array(
                    'Content-type: application/json',
                    'Authorization: bA2xcjpf8y5aSUFsNB2qN5yymUBSs6edsad7KhjassmAGkjnSAIjks3qHoFpGkec75RCeBb8cpKauGefw5qy4'
                ),
                CURLOPT_POSTFIELDS => $jsonData,
            );
            curl_setopt_array($ch, $options);
            $result =  curl_exec($ch);
            Log::info($result);
            curl_close($ch);
        }

        // UPDATE PRINTING DATE
        if (array_key_exists("card_status", $application->getChanges())) {
            if ($application->card_status == 3 || $application->card_status == 4 || $application->card_status == 5) {
                $application->update(['print_date' => Carbon::now()]);
            }
        }


        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function destroy($id)
    {
        $application = Application::findOrFail($id);
        $application->delete();
        return redirect()->back();
    }

    public function uploadProfileImage(Request $request, $id)
    {
        $application = Application::find($id);
        $directory = 'applications/profile-images/';
        if ($request->hasFile('profile_image_url')) {
            $fileName = $request->file('profile_image_url')->storeAs($directory, $application->cnic_no . '.png');
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = $directory . $application->cnic_no . '.png';
            $application->update([
                'profile_image_url' => $url,
                'profile_image_name' => $application->cnic_no,
            ]);
        }
    }

    public function uploadCnicFrontImage(Request $request, $id)
    {
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/' . $model->id;
        if ($request->hasFile('cnic_front_image_url')) {
            $fileName = $request->file('cnic_front_image_url')->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('cnic_front_image_url')));
            ($upload == NULL) ? LawyerUpload::create(['application_id' => $model->id, 'cnic_front' => $url]) : $upload->update(['cnic_front' => $url]);
        }
    }

    public function uploadCnicBackImage(Request $request, $id)
    {
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/' . $model->id;
        if ($request->hasFile('cnic_back_image_url')) {
            $fileName = $request->file('cnic_back_image_url')->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('cnic_back_image_url')));
            ($upload == NULL) ? LawyerUpload::create(['application_id' => $model->id, 'cnic_back' => $url]) : $upload->update(['cnic_back' => $url]);
        }
    }

    public function uploadIdCardFrontImage(Request $request, $id)
    {
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/' . $model->id;
        if ($request->hasFile('id_card_front_image_url')) {
            $fileName = $request->file('id_card_front_image_url')->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('id_card_front_image_url')));
            ($upload == NULL) ? LawyerUpload::create(['application_id' => $model->id, 'card_front' => $url]) : $upload->update(['card_front' => $url]);
        }
    }

    public function uploadIdCardBackImage(Request $request, $id)
    {
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/' . $model->id;
        if ($request->hasFile('id_card_back_image_url')) {
            $fileName = $request->file('id_card_back_image_url')->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('id_card_back_image_url')));
            ($upload == NULL) ? LawyerUpload::create(['application_id' => $model->id, 'card_back' => $url]) : $upload->update(['card_back' => $url]);
        }
    }

    public function pdfview(Request $request)
    {
        $application = Application::find($request->application);
        view()->share('application', $application);
        if ($request->has('download')) {
            $pdf = PDF::loadView('admin.applications.pdf-view');
            return $pdf->download('APPLICATION-' . $application->application_token_no . '.pdf');
        }
        return view('admin.applications.pdf-view');
    }

    public function print(Request $request, $id)
    {
        $application = Application::find($id);
        return view('admin.applications.print', compact('application'));
    }

    public function preview($id)
    {
        $application = Application::findOrFail($id);
        return view('admin.applications.preview', compact('application'));
    }

    public function finalSubmission(Request $request)
    {
        $application = Application::find($request->application_id);
        $data = array(
            "phone" => '+92' . $application->active_mobile_no,
            "message" => 'Your application has been submitted. Your Application Token No:' . $application->application_token_no,
        );

        $jsonData = json_encode($data);
        $json_url = "https://pb-staging.herokuapp.com/v1/external/send-sms";

        $ch = curl_init($json_url);
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Content-type: application/json',
                'Authorization: bA2xcjpf8y5aSUFsNB2qN5yymUBSs6edsad7KhjassmAGkjnSAIjks3qHoFpGkec75RCeBb8cpKauGefw5qy4'
            ),
            CURLOPT_POSTFIELDS => $jsonData,
        );
        curl_setopt_array($ch, $options);
        $result =  curl_exec($ch);
        Log::info($result);
        curl_close($ch);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function import(Request $request)
    {
        Excel::import(new ApplicationImport($request->excel_import_app_type), request()->file('excel_import_file'));
        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function export(Request $request)
    {
        try {
            return Excel::download(new ApplicationsExport($request->app_type, $request->app_date, $request->app_date_range), 'application.xlsx');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Choose Application Type. (Lower court, High Court, Renewal High Court, Renewal Lower Court)');
        }
    }

    public function uploadProfileImages(Request $request)
    {
        if ($request->isMethod('post')) {
            $directory = 'applications/profile-images/';

            if ($request->hasFile('profile_images')) {
                $originalName = $request->file('profile_images')->getClientOriginalName();
                $fileName = $request->file('profile_images')->storeAs($directory, $originalName);
                if (!Storage::exists($directory)) {
                    Storage::makeDirectory($directory);
                }
            }
        }

        return view('admin.applications.uploads');
    }

    public function unapproved(Request $request)
    {
        $applications = Application::where('is_approved', 0)
            ->whereIn('application_type', ['1', '2', '3', '4'])
            ->orderBy('id', 'DESC')
            ->get();

        if ($request->isMethod('post')) {
            $application = Application::find($request->application_id);
            $application->update(['is_approved' => TRUE]);

            return redirect()->back();
        }

        return view('admin.applications.unapproved', compact('applications'));
    }

    public function certificate(Request $request)
    {
        $application = Application::find($request->application);
        $printCertificate = PrintCertificate::where('application_id', $application->id)->first();

        if ($printCertificate == NULL) {
            PrintCertificate::create([
                'application_id' => $application->id,
                'printed' => TRUE,
                'address' => $application->postal_address,
            ]);
        }

        view()->share([
            'application' => $application,
        ]);

        if ($request->has('download')) {
            $pdf = PDF::loadView('admin.applications.certificate');
            $pdf->setPaper('Legal', 'portrait');
            return $pdf->stream('Certificate-' . $application->application_token_no . '.pdf', array("Attachment" => false));
        }

        return view('admin.applications.certificate');
    }

    public function printAddress(Request $request)
    {
        $address = PrintCertificate::select('id', 'application_id', 'address')->where('is_processed', 0)->get();
        view()->share([
            'address' => $address,
        ]);

        if ($request->has('download')) {
            $pdf = PDF::loadView('admin.applications.print-address');
            $pdf->setPaper('A4', 'portrait');
            foreach ($address as $item) {
                $item->is_processed = 1;
                $item->save();
            }
            return $pdf->stream('print-certificate-' . Carbon::now()->format('YmdHis') . '.pdf', array("Attachment" => false));
        }

        return view('admin.applications.print-address');
    }

    public function printAddressCount()
    {
        $address = PrintCertificate::select('id')->where('is_processed', 0)->get();
        return response()->json([
            'status' => 1,
            'count' => $address->count()
        ]);
    }

    public function reportPdf(Request $request)
    {
        $application_type = $request->report_application_type;
        $query = Application::select('*')->where('is_approved', 1)->whereNotIn('application_type', ['5', '6']);

        if (!empty($request->report_application_type)) {
            $query->where('application_type', $application_type);
        }
        if (!empty($request->report_application_date)) {
            if ($request->get('report_application_date') == '1') {
                $query->whereDate('created_at', Carbon::today());
            }
            if ($request->get('report_application_date') == '2') {
                $query->whereDate('created_at', Carbon::yesterday());
            }
            if ($request->get('report_application_date') == '3') {
                $date = Carbon::now()->subDays(7);
                $query->where('created_at', '>=', $date);
            }
            if ($request->get('report_application_date') == '4') {
                $date = Carbon::now()->subDays(30);
                $query->where('created_at', '>=', $date);
            }
            if ($request->report_application_date == 5) {
                if ($request->get('report_application_date_range')) {
                    $dateRange = explode(' - ', $request->report_application_date_range);
                    $from = date("Y-m-d", strtotime($dateRange[0]));
                    $to = date("Y-m-d", strtotime($dateRange[1]));
                    $query->whereBetween('created_at', [$from, $to]);
                }
            }
        }
        $applications = $query->get();

        view()->share([
            'applications' => $applications,
            'application_type' => $application_type,
        ]);

        $pdf = PDF::loadView('admin.reports.pdf');
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('Reports-' . Carbon::now() . '.pdf', array("Attachment" => false));
    }


    public function addQueue(Request $request)
    {
        $data = $request->selectedApplication;
        $ids = explode(',', $data);

        $ids = array_unique($ids);

        $admin = Auth::guard('admin')->user();
        foreach ($ids as $id) {
            $secureCardData = PrintSecureCard::updateOrCreate(
                [
                    'application_id' => $id,
                    'is_printed' => 0,
                    'admin_id' => $admin->id,
                ]
            );
        }

        return redirect()->back()->with('success', 'Add to list successfully');
    }

    public function selectAll(Request $request)
    {


        $application = Application::select('*');
        if ($request->get('application_type')) {
            $application->where('application_type', $request->get('application_type'));
        }

        if ($request->get('application_date')) {
            if ($request->get('application_date') == '1') {
                $application->whereDate('created_at', Carbon::today());
            }
            if ($request->get('application_date') == '2') {
                $application->whereDate('created_at', Carbon::yesterday());
            }
            if ($request->get('application_date') == '3') {
                $date = Carbon::now()->subDays(7);
                $application->where('created_at', '>=', $date);
            }
            if ($request->get('application_date') == '4') {
                $date = Carbon::now()->subDays(30);
                $application->where('created_at', '>=', $date);
            }
            if ($request->application_date == 5) {
                if ($request->get('application_date_range')) {
                    $dateRange = explode(' - ', $request->application_date_range);
                    $from = date("Y-m-d", strtotime($dateRange[0]));
                    $to = date("Y-m-d", strtotime($dateRange[1]));
                    $application->whereBetween('created_at', [$from, $to]);
                }
            }
        }

        if ($request->get('application_operator')) {
            $application->where('submitted_by', $request->application_operator);
        }

        if (!empty($request->get('search'))) {
            $application->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->orWhere('advocates_name', 'LIKE', "%$search%")
                    ->orWhere('cnic_no', 'LIKE', "%$search%")
                    ->orWhere('active_mobile_no', 'LIKE', "%$search%")
                    ->orWhere('postal_address', 'LIKE', "%$search%")
                    ->orWhere('address_2', 'LIKE', "%$search%")
                    ->orWhere('application_token_no', 'LIKE', "%$search%");
            });
        }

        $admin = Auth::guard('admin')->user();
        return response()->json(['application_id' => $application->pluck('id'), 'status' => 1]);
    }

    public function vpIndex(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $applications = PrintSecureCard::select('*')->where('is_printed', 0)->orderBy('id', 'DESC')->get();
        $operators = Admin::select('id', 'name')->get();
        $printedCertificates = PrintCertificate::select('id')->where('is_processed', 0)->get();

        if ($request->ajax()) {
            $application = PrintSecureCard::select('*')->where('is_printed', 0)->orderBy('id', 'DESC');
            $admin = Auth::guard('admin')->user();

            return Datatables::of($application)
                ->addIndexColumn()
                ->addColumn('checkbox', function (PrintSecureCard $data) {
                    $checkbox = '<input type="checkbox" class="application_checked" name="applications[]" value="' . $data->id . '"/>';
                    return $checkbox;
                })
                ->addColumn('application_id', function (PrintSecureCard $data) {

                    $btn = getApplicationTokenNo($data->application_id);
                    return $btn;
                })
                ->addColumn('submitted_by', function (PrintSecureCard $data) {
                    if (isset($data->admin_id)) {
                        $submittedBy = getAdminName($data->admin_id);
                    } else {
                        $submittedBy = "Online User";
                    }
                    return $submittedBy;
                })
                ->addColumn('action', function (PrintSecureCard $data) {

                    $btn = 'null';

                    return $btn;
                })

                ->rawColumns(['checkbox', 'action', 'submitted_by'])
                ->make(true);
        }

        return view('admin.applications.vp-listing', compact('applications', 'operators', 'printedCertificates'));
    }

    public function removeQueue(Request $request)
    {
        $data = $request->selectedApplication;
        $ids = explode(',', $data);

        $admin = Auth::guard('admin')->user();
        $items = PrintSecureCard::whereIn('id', $ids)->get();

        foreach ($items as $item) {
            $item->delete();
        }

        return redirect()->back()->with('success', 'Removed from list successfully');
    }

    public function exportBulk(Request $request)
    {
        $exports = PrintSecureCard::select('id', 'application_id')->where('is_printed', 0);
        if ($exports->count() == 0) {
            return redirect()->route('applications.vpIndex')->with('error', 'No record to export');
        }
        $ids = $exports->pluck('application_id')->toArray();
        $exports->update([
            'is_printed' => 1,
            'printed_at' => Carbon::now(),
        ]);
        if (empty($ids)) {
            return response()->json([
                'status' => 0,
                'message' => 'Nothing to export',
            ]);
        } else {
            return response()->json([
                'status' => 1,
                'ids' => implode(',', $ids),
                'pdf' => \URL::route('applications.exportPDFs'),
                'print' => \URL::route('applications.exportPrint'),
                'excel' => \URL::route('applications.exportExcel'),
            ]);
        }
    }

    public function exportPDFs(Request $request)
    {
        $ids = $request->ids;
        $ids = explode(',', $ids);
        $applications = Application::select('id', 'reg_no_lc')->whereIn('id', $ids)->get();
        view()->share([
            'applications' => $applications
        ]);
        $pdf = PDF::loadView('admin.applications.vp-print-1');
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Certificate.pdf', array("Attachment" => false));
    }

    public function exportPrint(Request $request)
    {
        $ids = $request->ids;
        $ids = explode(',', $ids);
        $applications = Application::select('id', 'postal_address', 'address_2', 'active_mobile_no', 'reg_no_lc')->whereIn('id', $ids)->get();
        view()->share([
            'applications' => $applications
        ]);
        $pdf = PDF::loadView('admin.applications.vp-print-2');
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Letter.pdf', array("Attachment" => false));
    }

    public function exportExcel(Request $request)
    {
        $appType = $request->app_type;
        $ids = explode(',', $request->ids);
        try {
            return Excel::download(new VPApplicationExport((int)$appType, $ids), 'application.xlsx');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Choose Application Type. (Lower court, High Court, Renewal High Court, Renewal Lower Court)');
        }
    }
}
