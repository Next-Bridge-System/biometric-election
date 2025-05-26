<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

use App\LawyerUpload;
use App\Application;
use App\Payment;
use App\Bar;
use App\Complaint;
use App\ComplaintFile;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PDF;
use Validator;

class FrontendController extends Controller
{
    public function dashboard()
    {
        return view('frontend.dashboard');
    }

    public function searchApplication(Request $request)
    {
        $application = NULL;

        if ($request->isMethod('post')) {
            $application = Application::where('application_token_no', $request->search_application)->orWhere('cnic_no', $request->search_application)->first();
        }

        return view('pages.search-application', compact('application'));
    }

    public function renewalHighCourt(Request $request)
    {
        $bars = Bar::select('id', 'name')->orderBy('name', 'asc')->get();

        if ($request->isMethod('post') && $request->ajax()) {

            $rules = [
                'advocates_name' => 'required|max:255',
                'so_of' => 'required|max:255',
                'license_no_hc' => 'required|max:255|unique:applications',
                'hcr_no' => 'required|max:255|unique:applications',
                'date_of_birth' => 'required|max:255',
                'date_of_enrollment_lc' => 'required|max:255',
                'date_of_enrollment_hc' => 'required|max:255',
                'cnic_no' => 'required|unique:applications',
                'postal_address' => 'required|max:255',
                'email' => 'nullable|email|max:255|unique:applications',
                'active_mobile_no' => 'required|numeric|digits:10|unique:applications',
                'voter_member_lc' => 'required|max:255',
                'voter_member_hc' => 'required|max:255',
                'bf_no_hc' => 'required|numeric',
                'profile_image_url' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'cnic_front_image_url' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'cnic_back_image_url' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'id_card_front_image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'id_card_back_image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'voucher_no' => 'required|max:255',
                'paid_date' => 'required|max:255',
                'voucher_file' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $data = [
                'application_type' => '3',
                'is_approved' => FALSE,
                'advocates_name' => $request->input('advocates_name'),
                'so_of' => $request->input('so_of'),
                'reg_no_lc' => $request->input('reg_no_lc'),
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
                'bf_no_hc' => $request->input('bf_no_hc'),
                'rf_id' => $request->input('rf_id'),
            ];

            $application = Application::create($data);
            $application->update([
                'application_token_no' => $application->id + 1000,
            ]);

            $this->uploadProfileImage($request, $application->id);
            $this->uploadCnicFrontImage($request, $application->id);
            $this->uploadCnicBackImage($request, $application->id);
            $this->uploadIdCardFrontImage($request, $application->id);
            $this->uploadIdCardBackImage($request, $application->id);

            $payment = Payment::create([
                'voucher_no' => $request->input('voucher_no'),
                'paid_date' => $request->input('paid_date'),
                'voucher_file' => $request->input('voucher_file'),
                'application_type' => 3,
                'application_id' => $application->id,
                'payment_status' => 1,
            ]);
            $this->uploadVoucher($request, $payment->id);

            $this->sendMessage($application);

            return response()->json([
                'status' => 1,
                'message' => 'success',
                'application' => $application->id,
            ]);
        }

        return view('pages.renewal-high-court', compact('bars'));
    }

    public function viewRenewalHighCourt($id)
    {
        $application = Application::findOrFail($id);
        $payment = Payment::where('application_id', $application->id)->where('application_type', 3)->first();

        return view('pages.view-renewal-high-court', compact('application', 'payment'));
    }

    public function renewalLowerCourt(Request $request)
    {
        $bars = Bar::select('id', 'name')->orderBy('name', 'asc')->get();

        if ($request->isMethod('post') && $request->ajax()) {

            $rules = [
                'advocates_name' => 'required|max:255',
                'so_of' => 'required|max:255',
                'date_of_birth' => 'required|max:255',
                'date_of_enrollment_lc' => 'required|max:255',
                'reg_no_lc' => 'required|max:255|unique:applications', // Legder No.
                'cnic_no' => 'required|unique:applications',
                'active_mobile_no' => 'required|numeric|digits:10|unique:applications',
                'license_no_lc' => 'required|max:255|unique:applications', // License No.
                'postal_address' => 'required|max:255',
                'email' => 'nullable|email|max:255|unique:applications',
                'voter_member_lc' => 'required|max:255',
                'profile_image_url' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'cnic_front_image_url' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'cnic_back_image_url' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'id_card_front_image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'id_card_back_image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'voucher_no' => 'required|max:255',
                'paid_date' => 'required|max:255',
                'voucher_file' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $data = [
                'application_type' => '4', // RENEWAL OF LOWER COURT
                'is_approved' => FALSE,
                'advocates_name' => $request->input('advocates_name'),
                'so_of' => $request->input('so_of'),
                'reg_no_lc' => $request->input('reg_no_lc'),
                'license_no_lc' => $request->input('license_no_lc'),
                'date_of_birth' => $request->input('date_of_birth'),
                'date_of_enrollment_lc' => $request->input('date_of_enrollment_lc'),
                'cnic_no' => $request->input('cnic_no'),
                'postal_address' => $request->input('postal_address'),
                'email' => $request->input('email'),
                'active_mobile_no' => $request->input('active_mobile_no'),
                'voter_member_lc' => $request->input('voter_member_lc'),
            ];

            $application = Application::create($data);

            $application->update([
                'application_token_no' => $application->id + 1000,
            ]);

            $this->uploadProfileImage($request, $application->id);
            $this->uploadCnicFrontImage($request, $application->id);
            $this->uploadCnicBackImage($request, $application->id);
            $this->uploadIdCardFrontImage($request, $application->id);
            $this->uploadIdCardBackImage($request, $application->id);
            $payment = Payment::create([
                'voucher_no' => $request->input('voucher_no'),
                'paid_date' => $request->input('paid_date'),
                'voucher_file' => $request->input('voucher_file'),
                'application_type' => 4,
                'application_id' => $application->id,
                'payment_status' => 1,
                'amount' => 750,
            ]);
            $this->uploadVoucher($request, $payment->id);
            $this->sendMessage($application);

            return response()->json([
                'status' => 1,
                'message' => 'success',
                'application' => $application->id,
            ]);
        }

        return view('pages.renewal-lower-court', compact('bars'));
    }

    public function viewRenewalLowerCourt($id)
    {
        $application = Application::findOrFail($id);
        $payment = Payment::where('application_id', $application->id)->where('application_type', 4)->first();

        return view('pages.view-renewal-lower-court', compact('application', 'payment'));
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

    public function uploadVoucher(Request $request, $id)
    {
        $model = Payment::find($id);
        $directory = 'applications/vouchers/' . $model->id;
        if ($request->hasFile('voucher_file')) {
            $fileName = $request->file('voucher_file')->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('voucher_file')));
            $model->update(['voucher_file' => $url]);
        }
    }

    public function complaints(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {

            $rules = [
                'name' => 'required|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'required|unique:users|regex:/(3)[0-9]{9}/',
                'type' => 'required|max:255',
                'files' => 'nullable|max:1024',
                'message' => 'required|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $data = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'type' => $request->input('type'),
                'message' => $request->input('message'),
            ];

            $complaint = Complaint::create($data);

            $directory = 'complaints/files/' . $complaint->id;
            if ($request->hasFile('files')) {
                $files = $request->file('files');
                foreach ($files as $file) {
                    $fileName = $file->getClientOriginalName();
                    if (!Storage::exists($directory)) {
                        Storage::makeDirectory($directory);
                    }
                    $url = Storage::putFile($directory, new File($file));
                    ComplaintFile::create([
                        'complaint_id' => $complaint->id,
                        'file_url' => $url,
                        'file_name' => $fileName,
                    ]);
                }
            }

            return response()->json([
                'status' => 1,
                'message' => 'The complaint has been sent successfully. We will contact you soon. Thankyou.',
            ]);
        }

        return view('pages.complaints');
    }

    public function lawyerRequestVerification()
    {
        return view('pages.lawyer-request-verification');
    }

    public function lahoreBarLawyers($cnic_no = NULL, $phone_no = NULL)
    {
        return view('pages.lahore-bar-lawyers', compact('cnic_no', 'phone_no'));
    }

    public function lahoreBarLawyersVoucher($cnic_no)
    {
        $user = User::where('cnic_no', $cnic_no)->first();

        $voucher_no = 5000000000000 + $user->id;

        view()->share([
            'user' => $user,
            'voucher_no' => $voucher_no,
        ]);

        $pdf = PDF::loadView('frontend.lahore-bar.voucher');
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('HBL-' . Carbon::now() . '.pdf', array("Attachment" => false));

        return view('frontend.lahore-bar.voucher');
    }

    public function highcourtLawyers($cnic_no = NULL)
    {
        return view('pages.highcourt-lawyers', compact('cnic_no'));
    }
}
