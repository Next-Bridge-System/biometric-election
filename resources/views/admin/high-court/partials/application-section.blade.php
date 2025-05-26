<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Application Information</legend>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-sm table-bordered" id="lc-table">
                <tr>
                    <th>User/Application No:</th>
                    <th class="text-center text-lg" colspan="2">
                        {{$application->user_id}}/{{$application->id}}
                    </th>
                    <th class="text-center">
                        @include('admin.high-court.partials.application-status')
                    </th>
                </tr>
                <tr>
                    <th>Application Type:</th>
                    <td>High Court</td>
                    <th>Application Status:</th>
                    <td>
                        @if ($application->hc_exemption_at)
                        <span class="badge badge-warning">
                            Exemption
                        </span>
                        @endif

                        <span>{!!appStatus($application->app_status,$application->app_type)!!}</span>
                       
                    </td>
                </tr>

                @if (Route::currentRouteName() == 'high-court.show')

                <tr>
                    <th>Payment Type:</th>
                    <td>Habib Bank Limited</td>
                    <th>Payment Status:</th>
                    <td>
                        <span class="badge badge-{{ getHcPaymentStatus($application->id)['badge'] }}">
                            {{ getHcPaymentStatus($application->id)['name'] }}</span>
                    </td>
                </tr>

                <tr>
                    <th>LC Ledger No:</th>
                    <td>
                        @component('admin.high-court.components.number')
                        @slot('type') lc_ledger @endslot
                        @slot('application_id') {{$application->id}} @endslot
                        @endcomponent
                    </td>

                    <th>LC Last Status:</th>
                    <td>
                        {{ isset($application->lc_last_status) ? $application->lc_last_status : "N/A" }}
                    </td>
                </tr>

                <tr>
                    <td class="bg-success" colspan="4"></td>
                </tr>

                <tr>
                    <th>RCPT #:</th>
                    <td>
                        @component('admin.high-court.components.number')
                        @slot('type') rcpt_no_hc @endslot
                        @slot('application_id') {{$application->id}} @endslot
                        @endcomponent
                    </td>

                    <th>BF No:</th>
                    <td>
                        @component('admin.high-court.components.number')
                        @slot('type') bf_no_hc @endslot
                        @slot('application_id') {{$application->id}} @endslot
                        @endcomponent
                    </td>
                </tr>

                <tr>
                    <th> HC License No:</th>
                    <td>
                        @component('admin.high-court.components.number')
                        @slot('type') license_no_hc @endslot
                        @slot('application_id') {{$application->id}} @endslot
                        @endcomponent
                    </td>
                    <th>HCR No:</th>
                    <td>
                        @component('admin.high-court.components.number')
                        @slot('type') hcr_no_hc @endslot
                        @slot('application_id') {{$application->id}} @endslot
                        @endcomponent
                    </td>
                </tr>

                <tr>
                    <th>Licence LC:</th>
                    <td>
                        @component('admin.high-court.components.number')
                        @slot('type') lc_lic @endslot
                        @slot('application_id') {{$application->id}} @endslot
                        @endcomponent
                    </td>

                    <th>BF Ledger No:</th>
                    <td>
                        @component('admin.high-court.components.number')
                        @slot('type') bf_ledger_no @endslot
                        @slot('application_id') {{$application->id}} @endslot
                        @endcomponent
                    </td>
                </tr>

                <tr>
                    <td class="bg-success" colspan="4"></td>
                </tr>

                <tr>
                    <th>Final LC Date:</th>
                    <td>
                        <span>{{getDateFormat($application->enr_date_lc)}}</span>
                        @if (permission('update_lc_date'))
                        @include('admin.high-court.partials.lc-date')
                        @endif
                    </td>
                    <th>Final HC Date:</th>
                    <td>
                        <span>{{getDateFormat($application->enr_date_hc)}}</span>
                        @if (permission('update_lc_date'))
                        @include('admin.high-court.partials.hc-date')
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>LC Expiry Date:</th>
                    <td>
                        <span>{{getDateFormat($application->lc_exp_date)}}</span>
                        @include('admin.high-court.partials.lc-expiry-date')
                    </td>

                    <th>Submission Date:</th>
                    <td>
                        <span>{{isset($application->final_submitted_at) ? date('d-m-Y',
                            strtotime($application->final_submitted_at)) : 'N/A'}}</span>
                    </td>
                </tr>

                <tr>
                    <th>Created at:</th>
                    <td>
                        <span>{{date('d-m-Y', strtotime($application->created_at))}}</span>
                    </td>
                    <th>Updated at:</th>
                    <td>
                        <span>{{date('d-m-Y', strtotime($application->updated_at))}}</span>
                    </td>
                </tr>

                @endif

            </table>
        </div>
    </div>
</fieldset>

<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Lawyer Information</legend>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered" id="lc-table">
                @if (Route::currentRouteName() == 'high-court.show' && permission('edit-high-court'))
                <tr>
                    <livewire:admin.high-court.edit-component :high_court_id="$application->id" :type="'LAWYER'" />
                </tr>
                @endif
                <tr>
                    <th>Lawyer Name:</th>
                    <td>{{$application->lawyer_name ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Father's / Husband's Name:</th>
                    <td>{{$application->user->father_name ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Gender:</th>
                    <td>{{$application->user->gender ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Date of Birth (As per CNIC):</th>
                    <td>{{getDateFormat($application->user->date_of_birth)}}</td>
                </tr>
                <tr>
                    <th>Age</th>
                    <td>{{convertLcAgeToWords($application->age)}}</td>
                </tr>
                <tr>
                    <th>Blood:</th>
                    <td>{{$application->user->blood ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td>{{$application->user->email ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Mobile No:</th>
                    <td>{{$application->mobile_no ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>CNIC:</th>
                    <td>{{$application->user->cnic_no ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Date of Expiry (CNIC):</th>
                    <td>{{getDateFormat($application->user->cnic_expired_at)}}</td>
                </tr>
            </table>
        </div>
    </div>
</fieldset>

<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Bar Association</legend>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered" id="lc-table">
                @if (Route::currentRouteName() == 'high-court.show' && permission('edit-high-court'))
                <tr>
                    <livewire:admin.high-court.edit-component :high_court_id="$application->id" :type="'BAR'" />
                </tr>
                @endif
                <tr>
                    <th>Bar Association / Voter Member:</th>
                    <td>{{$application->voterMemberHc->name ?? '-'}}</td>
                </tr>
            </table>
        </div>
    </div>
</fieldset>

<fieldset class="border p-4 mb-4 page-break">
    <legend class="w-auto">Address Information</legend>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered" id="lc-table">
                @if (Route::currentRouteName() == 'high-court.show' && permission('edit-high-court'))
                <tr>
                    <livewire:admin.high-court.edit-component :high_court_id="$application->id" :type="'ADDRESS'" />
                </tr>
                @endif
                <tr class="bg-success text-center">
                    <th colspan="2">Home Address</th>
                </tr>
                <tr>
                    <th>House #:</th>
                    <td>{{$application->address->ha_house_no ?? 'N/A'}}</td>
                </tr>

                <tr>
                    <th>City:</th>
                    <td>{{$application->address->ha_city ?? 'N/A'}}</td>
                </tr>

                <tr>
                    <th>Country:</th>
                    <td>{{isset($application->address->ha_country_id) ?
                        getCountryName($application->address->ha_country_id): 'N/A'}}</td>
                </tr>

                @if (isset($application->address->ha_country_id) &&
                $application->address->ha_country_id == 166)
                <tr>
                    <th>Province/State:</th>
                    <td>{{getProvinceName($application->address->ha_province_id) ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>District:</th>
                    <td>{{getDistrictName($application->address->ha_district_id) ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Tehsil:</th>
                    <td>{{getTehsilName($application->address->ha_tehsil_id) ?? 'N/A'}}</td>
                </tr>
                @endif

                <tr class="bg-success text-center">
                    <th colspan="2">Postal Address</th>
                </tr>
                <tr>
                    <th>House #:</th>
                    <td>{{$application->address->pa_house_no ?? 'N/A'}}</td>
                </tr>

                <tr>
                    <th>Country:</th>
                    <td>{{isset($application->address->pa_country_id) ?
                        getCountryName($application->address->pa_country_id) : 'N/A'}}</td>
                </tr>

                @if (isset($application->address->pa_country_id) &&
                $application->address->pa_country_id == 166)
                <tr>
                    <th>Province/State:</th>
                    <td>{{getProvinceName($application->address->pa_province_id) ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>District:</th>
                    <td>{{getDistrictName($application->address->pa_district_id) ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Tehsil:</th>
                    <td>{{getTehsilName($application->address->pa_tehsil_id) ?? 'N/A'}}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>
</fieldset>


@if (in_array(Route::currentRouteName(),['high-court.show','frontend.high-court.show']))
@include('admin.high-court.partials.image-upload-section')
@endif