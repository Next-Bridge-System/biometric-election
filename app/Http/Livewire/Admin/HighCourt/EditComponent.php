<?php

namespace App\Http\Livewire\Admin\HighCourt;

use App\Bar;
use App\Country;
use App\District;
use App\HighCourt;
use App\Province;
use App\Tehsil;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditComponent extends Component
{
    public $type;
    public $high_court_id, $high_court;
    public $first_name, $last_name, $father_name, $gender, $date_of_birth, $blood, $email, $phone, $cnic_no, $cnic_expired_at;
    public $voter_member_hc, $bars = [];
    public $countries = [], $provinces = [], $districts = [], $tehsils = [];
    public $ha_house_no, $ha_city, $ha_country_id, $ha_province_id, $ha_district_id, $ha_tehsil_id;
    public $pa_house_no, $pa_city, $pa_country_id, $pa_province_id, $pa_district_id, $pa_tehsil_id;

    public function mount()
    {
        $this->high_court = HighCourt::with('user', 'address')->where('id', $this->high_court_id)->first();
        $this->bars = Bar::select('id', 'name')->orderBy('name', 'asc')->get();
        $this->countries = Country::select('id', 'name')->orderBy('name', 'asc')->get();
        $this->provinces = Province::select('id', 'name')->orderBy('name', 'asc')->get();
        $this->districts = District::select('id', 'name', 'code')->orderBy('name', 'asc')->get();
        $this->tehsils = Tehsil::select('id', 'name', 'code')->orderBy('name', 'asc')->get();
    }

    public function render()
    {
        return view('livewire.admin.high-court.edit-component');
    }

    public function edit()
    {
        if ($this->type == 'LAWYER') {
            $this->first_name = $this->high_court->user->fname;
            $this->last_name = $this->high_court->user->lname;
            $this->father_name = $this->high_court->user->father_name;
            $this->gender = $this->high_court->user->gender;
            $this->date_of_birth = $this->high_court->user->date_of_birth;
            $this->blood = $this->high_court->user->blood;
            $this->email = $this->high_court->user->email;
            $this->phone = $this->high_court->user->phone;
            $this->cnic_no = $this->high_court->user->cnic_no;
            $this->cnic_expired_at = $this->high_court->user->cnic_expired_at;
        }

        if ($this->type == 'BAR') {
            $this->voter_member_hc = $this->high_court->voter_member_hc;
        }

        if ($this->type == 'ADDRESS') {
            $this->ha_house_no = $this->high_court->address->ha_house_no;
            $this->ha_city = $this->high_court->address->ha_city;
            $this->ha_country_id = $this->high_court->address->ha_country_id;
            $this->ha_province_id = $this->high_court->address->ha_province_id;
            $this->ha_district_id = $this->high_court->address->ha_district_id;
            $this->ha_tehsil_id = $this->high_court->address->ha_tehsil_id;

            $this->pa_house_no = $this->high_court->address->pa_house_no;
            $this->pa_city = $this->high_court->address->pa_city;
            $this->pa_country_id = $this->high_court->address->pa_country_id;
            $this->pa_province_id = $this->high_court->address->pa_province_id;
            $this->pa_district_id = $this->high_court->address->pa_district_id;
            $this->pa_tehsil_id = $this->high_court->address->pa_tehsil_id;
        }
    }

    public function update()
    {
        if ($this->type == 'LAWYER') {
            $this->validate([
                'first_name' => 'required|max:15',
                'last_name' => 'required|max:15',
                'father_name' => 'required|max:25',
                'gender' => 'required|in:Male,Female,Other',
                'date_of_birth' => 'required|date',
                'blood' => 'required|max:5',
                'email' => ['required', 'email', Rule::unique('users')->ignore($this->high_court->user->id, 'id')],
                'cnic_no' => ['required', 'regex:/^\d{5}-\d{7}-\d{1}$/', Rule::unique('users')->ignore($this->high_court->user->id, 'id')], // Corrected regex pattern
                'phone' => ['required', 'regex:/^3\d{9}$/', Rule::unique('users')->ignore($this->high_court->user->id, 'id')], // Corrected regex pattern
                'cnic_expired_at' => 'required|date',
            ]);

            $this->high_court->user->update([
                'name' => $this->first_name . ' ' . $this->last_name,
                'fname' => $this->first_name,
                'lname' => $this->last_name,
                'father_name' => $this->father_name,
                'gender' => $this->gender,
                'date_of_birth' => $this->date_of_birth,
                'blood' => $this->blood,
                'email' => $this->email,
                'phone' => $this->phone,
                'cnic_no' => $this->cnic_no,
                'cnic_expired_at' => $this->cnic_expired_at,
            ]);

            $final_date = isset($this->high_court->final_submitted_at) ? $this->high_court->final_submitted_at : $this->high_court->created_at;
            $age  = Carbon::parse($this->high_court->user->date_of_birth)->diff($final_date)->format('%y.%m');

            $this->high_court->update([
                'age' => $age
            ]);
        }


        if ($this->type == 'BAR') {
            $this->validate([
                'voter_member_hc' => 'required',
            ]);

            $this->high_court->update([
                'voter_member_hc' => $this->voter_member_hc,
            ]);
        }

        if ($this->type == 'ADDRESS') {
            $this->validate([
                'ha_house_no' => 'required',
                'ha_city' => 'required',
                'ha_country_id' => 'required',
                'ha_province_id' => 'required',
                'ha_district_id' => 'required',
                'ha_tehsil_id' => 'required',

                'pa_house_no' => 'required',
                'pa_city' => 'required',
                'pa_country_id' => 'required',
                'pa_province_id' => 'required',
                'pa_district_id' => 'required',
                'pa_tehsil_id' => 'required',
            ]);

            $this->high_court->address->update([
                'ha_house_no' => $this->ha_house_no,
                'ha_city' => $this->ha_city,
                'ha_country_id' => $this->ha_country_id,
                'ha_province_id' => $this->ha_province_id,
                'ha_district_id' => $this->ha_district_id,
                'ha_tehsil_id' => $this->ha_tehsil_id,

                'pa_house_no' => $this->pa_house_no,
                'pa_city' => $this->pa_city,
                'pa_country_id' => $this->pa_country_id,
                'pa_province_id' => $this->pa_province_id,
                'pa_district_id' => $this->pa_district_id,
                'pa_tehsil_id' => $this->pa_tehsil_id,
            ]);
        }

        return redirect()->route('high-court.show', $this->high_court->id);
    }
}
