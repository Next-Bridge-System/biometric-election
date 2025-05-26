<?php

namespace App\Http\Livewire\Frontend\LawyerRequests;

use App\Bar;
use App\Certificate;
use Livewire\Component;
use App\CertificateTemplate;
use App\GcHighCourt;
use App\GcLowerCourt;
use App\HighCourt;
use App\LawyerRequest;
use App\LawyerRequestCategory;
use App\LawyerRequestSubCategory;
use App\LowerCourt;
use App\Payment;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;

class CreateComponent extends Component
{
    use WithFileUploads;

    public
        $lawyer_name,
        $father_name,
        $cnic_no,
        $license_no,
        $enr_date_lc,
        $enr_date_hc,

        $embassy_name,
        $visit_country,
        $society_name,
        $reason,

        $voter_member,
        $lawyer_type,
        $lawyer_request_category_id,
        $lawyer_request_sub_category_id,
        $address;

    public
        $lawyer_request_id,
        $lawyer_request_categories,
        $lawyer_request_sub_categories,
        $user,
        $bars;

    public
        $voucher_no,
        $voucher_name,
        $voucher_file,
        $lawyer_request_sent = false;


    public function mount()
    {
        $this->bars = Bar::get();
        $this->user = User::where('id', Auth::guard('frontend')->id())->first();
        $this->lawyer_request_categories = LawyerRequestCategory::where('status', 1)->get();

        if ($this->user->register_as == 'gc_lc') {
            $application = GcLowerCourt::where('user_id', $this->user->id)->first();
            $this->lawyer_name = $application->lawyer_name;
            $this->father_name = $application->father_name;
            $this->cnic_no = $application->cnic_no;
            $this->license_no = $application->license_no_lc;
            $this->enr_date_lc = $application->date_of_enrollment_lc;
            $this->address = $application->address_1;
            $this->voter_member = $application->voter_member_lc;

            if ($application->app_status == 1) {
                $this->lawyer_request_sub_categories = LawyerRequestSubCategory::whereIn('type', ['general', 'lc'])->where('status', 1)->get();
            }
        }

        if ($this->user->register_as == 'gc_hc') {
            $application = GcHighCourt::where('user_id', $this->user->id)->first();
            $this->lawyer_name = $application->lawyer_name;
            $this->father_name = $application->father_name;
            $this->cnic_no = $application->cnic_no;
            $this->license_no = $application->license_no_hc;
            $this->enr_date_lc = $application->enr_date_lc;
            $this->enr_date_hc = $application->enr_date_hc;
            $this->address = $application->address_1;
            $this->voter_member = $application->voter_member_hc;

            if ($application->app_status == 1) {
                $this->lawyer_request_sub_categories = LawyerRequestSubCategory::whereIn('type', ['general', 'lc', 'hc'])->where('status', 1)->get();
            } else {
                $this->lawyer_request_sub_categories = LawyerRequestSubCategory::whereIn('type', ['general', 'lc'])->where('status', 1)->get();
            }
        }

        if ($this->user->register_as == 'lc') {
            $application = LowerCourt::where('user_id', $this->user->id)->first();
            $this->lawyer_name = $application->lawyer_name;
            $this->father_name = $application->father_name;
            $this->cnic_no = $application->cnic_no;
            $this->license_no = $application->license_no_lc;
            $this->enr_date_lc = $application->lc_date;
            $this->address = getLcPostalAddress($application->id);
            $this->voter_member = $application->voter_member_lc;

            if ($application->app_status == 1) {
                $this->lawyer_request_sub_categories = LawyerRequestSubCategory::whereIn('type', ['general', 'lc'])->where('status', 1)->get();
            }
        }

        if ($this->user->register_as == 'hc') {
            $application = HighCourt::where('user_id', $this->user->id)->first();
            $this->lawyer_name = $application->user->name;
            $this->father_name = $application->user->father_name;
            $this->cnic_no = $application->user->cnic_no;
            $this->license_no = $application->license_no_hc;
            $this->enr_date_lc = $application->enr_date_lc;
            $this->enr_date_hc = $application->enr_date_hc;
            $this->address = getHcPostalAddress($application->id);
            $this->voter_member = $application->voter_member_hc;

            if ($application->app_status == 1) {
                $this->lawyer_request_sub_categories = LawyerRequestSubCategory::whereIn('type', ['general', 'lc', 'hc'])->where('status', 1)->get();
            } else {
                $this->lawyer_request_sub_categories = LawyerRequestSubCategory::whereIn('type', ['general', 'lc'])->where('status', 1)->get();
            }
        }
    }

    public function render()
    {
        return view('livewire.frontend.lawyer-requests.create-component');
    }

    public function store()
    {
        $data = $this->validate([
            'lawyer_request_category_id' => 'required',
            'lawyer_request_sub_category_id' => 'required',
            'visit_country' => [Rule::requiredIf($this->lawyer_request_sub_category_id == 1)],
            'embassy_name' => [Rule::requiredIf($this->lawyer_request_sub_category_id == 1)],
            'society_name' => [Rule::requiredIf($this->lawyer_request_sub_category_id == 8)],
            'reason' => 'required|min:10',
        ]);

        $lawyer_request_sub_category = LawyerRequestSubCategory::find($this->lawyer_request_sub_category_id);

        $lawyer_request = LawyerRequest::create([
            "user_id" => $this->user->id,
            "lawyer_type" => $this->user->register_as,
            "lawyer_request_category_id" => $lawyer_request_sub_category->lawyer_request_category_id,
            "lawyer_request_sub_category_id" => $lawyer_request_sub_category->id,
            "amount" => $lawyer_request_sub_category->amount,
            "lawyer_name" => $this->lawyer_name,
            "father_name" => $this->father_name,
            "cnic_no" => $this->cnic_no,
            "license_no" => $this->license_no,
            "enr_date_lc" => $this->enr_date_lc,
            "enr_date_hc" => $this->enr_date_hc,
            "bar_id" => $this->voter_member,

            "embassy_name" => $data['embassy_name'],
            "visit_country" => $data['visit_country'],
            "society_name" => $data['society_name'],
            "reason" => $data['reason'],
        ]);

        $this->lawyer_request_sent = true;
        $this->lawyer_request_id = $lawyer_request->id;
        $this->payment();
    }

    private function payment()
    {
        $lawyer_request = LawyerRequest::with('lawyer_request_category', 'lawyer_request_sub_category')->find($this->lawyer_request_id);

        $payment = Payment::create([
            'payment_module' => 'LAWYER_REQUESTS',
            'lawyer_request_id' => $lawyer_request->id,
            'user_id' => Auth::guard('frontend')->user()->id,
            'voucher_name' => strtoupper($lawyer_request->lawyer_request_sub_category->name),
            'amount' => $lawyer_request->lawyer_request_sub_category->amount,
        ]);

        $payment->update([
            'voucher_no' => get_lawyer_request_voucher_no($payment->id),
        ]);

        $this->voucher_no = $payment->voucher_no;
        $this->voucher_name = $payment->voucher_name;
    }
}
