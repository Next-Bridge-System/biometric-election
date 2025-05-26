<div class="row">
    <div class="table-responsive">
        <table class="table table-striped table-sm table-bordered" id="senior_lawyer_info_table">
            <tr>
                <th>Sr. Lawyer Name :</th>
                <td>{{$application->srl_name ?? 'N/A'}}</td>
            </tr>
            <tr>
                <th>Advocate High Court / Subordinate Courts (Bar Name) :</th>
                <td>{{$application->srlBar->name ?? 'N/A'}}</td>
            </tr>
            <tr>
                <th>Sr. Lawyer Office Address :</th>
                <td>{{$application->srl_office_address ?? 'N/A'}}</td>
            </tr>
            <tr>
                <th>Sr. Lawyer Enrollment Date :</th>
                <td>{{isset($application->srl_enr_date) ? date('d-m-Y',
                    strtotime($application->srl_enr_date)) : 'N/A'}}</td>
            </tr>
            <tr>
                <th>Sr. Lawyer Joining Date :</th>
                <td>{{isset($application->srl_joining_date) ? date('d-m-Y',
                    strtotime($application->srl_joining_date)) : 'N/A'}}</td>
            </tr>
            <tr>
                <th>Sr. Lawyer Mobile Number :</th>
                <td>{{isset($application->srl_mobile_no) ? '+92'.$application->srl_mobile_no :
                    'N/A'}}</td>
            </tr>
            <tr>
                <th>Sr. Lawyer CNIC Number :</th>
                <td>{{$application->srl_cnic_no ?? 'N/A'}}</td>
            </tr>
            <tr>
                <th>Sr. Lawyer CNIC (Front) :</th>
                <th>Sr. Lawyer CNIC (Back) :</th>
            </tr>
            <tr>
                <td>
                    @if (isset($application->uploads->srl_cnic_front))
                    <img src="{{asset('storage/app/public/'.$application->uploads->srl_cnic_front)}}" alt=""
                        class="custom-image-preview"> @else N/A @endif
                </td>
                <td>
                    @if (isset($application->uploads->srl_cnic_back))
                    <img src="{{asset('storage/app/public/'.$application->uploads->srl_cnic_back)}}" alt=""
                        class="custom-image-preview"> @else N/A @endif
                </td>
            </tr>
            <tr>
                <th>Sr. Lawyer License (Front) :</th>
                <th>Sr. Lawyer License (Back) :</th>
            </tr>
            <tr>
                <td>
                    @if (isset($application->uploads->srl_license_front))
                    <img src="{{asset('storage/app/public/'.$application->uploads->srl_license_front)}}" alt=""
                        class="custom-image-preview"> @else N/A @endif
                </td>
                <td>
                    @if (isset($application->uploads->srl_license_back))
                    <img src="{{asset('storage/app/public/'.$application->uploads->srl_license_back)}}" alt=""
                        class="custom-image-preview"> @else N/A @endif
                </td>
            </tr>
        </table>
    </div>
</div>
