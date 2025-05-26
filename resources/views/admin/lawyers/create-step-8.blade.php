@extends('layouts.admin')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Existing Lawyers</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('lawyers.index')}}" class="btn btn-dark">Back</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Add Existing Lawyer</h3>
                    </div>

                    @include('admin.lawyers.partials.steps')

                    <form action="#" method="POST" id="create_step_8_form" enctype="multipart/form-data"> @csrf
                        <div class="card-body">
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Application Information</legend>
                                <div class="row">
                                    <table class="table table-striped table-sm table-bordered">
                                        <tr>
                                            <th>Application Token No:</th>
                                            <th colspan="3" class="text-center text-lg">
                                                {{$lawyer->application_token_no ?? 'N/A'}}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Application Type:</th>
                                            <td>
                                                {{$lawyer->application_type == 1 ? 'Lower Court' : 'High Court'}}
                                            </td>
                                            <th>Application Status:</th>
                                            <td>
                                                @if ($lawyer->application_status == 1) Active
                                                @elseif($lawyer->application_status == 2) Suspended
                                                @elseif($lawyer->application_status == 3) Died
                                                @elseif($lawyer->application_status == 4) Removed
                                                @elseif($lawyer->application_status == 5) Transfer in
                                                @elseif($lawyer->application_status == 6) Transfer out
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Application Submitted By:</th>
                                            <td>{{getAdminName($lawyer->submitted_by)}}</td>

                                            <th>Application Submitted At:</th>
                                            <td>{{$lawyer->created_at->format('F d, Y - h:i:s A')}}</td>
                                        </tr>
                                        @if (isset($lawyer->updated_by))
                                        <tr>
                                            <th>Application Updated By:</th>
                                            <td>{{getAdminName($lawyer->updated_by)}}</td>

                                            <th>Application Updated At:</th>
                                            <td>{{$lawyer->updated_at->format('F d, Y - h:i:s A')}}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </fieldset>
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Personal Information</legend>
                                <div class="row">
                                    <table class="table table-striped table-sm table-bordered">
                                        <tr>
                                            <th>First Name:</th>
                                            <td>{{$lawyer->advocates_name ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Name:</th>
                                            <td>{{$lawyer->last_name ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Father's / Husband's Name:</th>
                                            <td>{{$lawyer->so_of ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Gender:</th>
                                            <td>{{$lawyer->gender ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Date of Birth (As per CNIC):</th>
                                            <td>{{$lawyer->date_of_birth ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Profile Picture:</th>
                                            <td>
                                                <img src="{{asset('storage/app/public/'.$lawyer->uploads->profile_image)}}"
                                                    class="custom-image-preview" alt="">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Contact Information</legend>
                                <div class="row">
                                    <table class="table table-striped table-sm table-bordered">
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{$lawyer->email ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Active Mobile No:</th>
                                            <td>{{$lawyer->active_mobile_no ?? 'N/A'}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Legal Identification</legend>
                                <div class="row">
                                    <table class="table table-striped table-sm table-bordered">
                                        <tr>
                                            <th>C.N.I.C. No:</th>
                                            <td>{{$lawyer->cnic_no ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Date of Expiry (CNIC):</th>
                                            <td>{{$lawyer->cnic_expiry_date ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Upload CNIC (Front):</th>
                                            <td>
                                                <img src="{{asset('storage/app/public/'.$lawyer->uploads->cnic_front)}}"
                                                    class="custom-image-preview" alt="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Upload CNIC (Back):</th>
                                            <td>
                                                <img src="{{asset('storage/app/public/'.$lawyer->uploads->cnic_back)}}"
                                                    class="custom-image-preview" alt="">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Address Information</legend>
                                <div class="row">
                                    <table class="table table-striped table-sm table-bordered">
                                        <tr class="bg-success text-center">
                                            <th colspan="2">Home Address</th>
                                        </tr>
                                        <tr>
                                            <th>House #:</th>
                                            <td>{{$lawyer->address->ha_house_no ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Street Address:</th>
                                            <td>{{$lawyer->address->ha_str_address ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Town/Mohalla:</th>
                                            <td>{{$lawyer->address->ha_town ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>City:</th>
                                            <td>{{$lawyer->address->ha_city ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Postal Code:</th>
                                            <td>{{$lawyer->address->ha_postal_code ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Country:</th>
                                            <td>{{getCountryName($lawyer->address->ha_country_id) ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Province/State:</th>
                                            <td>{{getProvinceName($lawyer->address->ha_province_id) ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>District:</th>
                                            <td>{{getDistrictName($lawyer->address->ha_district_id) ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Tehsil:</th>
                                            <td>{{getTehsilName($lawyer->address->ha_tehsil_id) ?? 'N/A'}}</td>
                                        </tr>
                                        <tr class="bg-success text-center">
                                            <th colspan="2">Postal Address</th>
                                        </tr>
                                        <tr>
                                            <th>House #:</th>
                                            <td>{{$lawyer->address->pa_house_no ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Street Address:</th>
                                            <td>{{$lawyer->address->pa_str_address ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Town/Mohalla:</th>
                                            <td>{{$lawyer->address->pa_town ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>City:</th>
                                            <td>{{$lawyer->address->pa_city ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Postal Code:</th>
                                            <td>{{$lawyer->address->pa_postal_code ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Country:</th>
                                            <td>{{getCountryName($lawyer->address->pa_country_id) ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Province/State:</th>
                                            <td>{{getProvinceName($lawyer->address->pa_province_id) ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>District:</th>
                                            <td>{{getDistrictName($lawyer->address->pa_district_id) ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Tehsil:</th>
                                            <td>{{getTehsilName($lawyer->address->pa_tehsil_id) ?? 'N/A'}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Academic Record</legend>
                                <div class="row">
                                    <table class="table table-sm text-center table-bordered">
                                        <tr class="bg-light">
                                            <th>#</th>
                                            <th>Qualification</th>
                                            <th>Institute</th>
                                            <th>Total Marks</th>
                                            <th>Obtained Marks</th>
                                            <th>Passing Year</th>
                                            <th>Roll No</th>
                                            <th>Certificate</th>
                                        </tr>
                                        @foreach ($lawyer->educations as $education)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                {{$education->qualification ?? 'N/A'}} /
                                                {{$education->sub_qualification ?? 'N/A'}}
                                            </td>
                                            <td>{{$education->institute->name ?? 'N/A'}}</td>
                                            <td>{{$education->total_marks ?? 'N/A'}}</td>
                                            <td>{{$education->obtained_marks ?? 'N/A'}}</td>
                                            <td>{{$education->passing_year ?? 'N/A'}}</td>
                                            <td>{{$education->roll_no ?? 'N/A'}}</td>
                                            <td>
                                                <a href="{{asset('storage/app/public/'.$education->certificate)}}"
                                                    download="certificate">
                                                    <span class="badge badge-success">Download</span>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </fieldset>
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Lawyer Information</legend>
                                <div class="row">
                                    <table class="table table-striped table-sm table-bordered">
                                        <tr>
                                            <th>License No. (LC):</th>
                                            <td>{{$lawyer->license_no_lc ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>License No. (HC):</th>
                                            <td>{{$lawyer->license_no_hc ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>PLJ No. (LC):</th>
                                            <td>{{$lawyer->plj_no_lc ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>BF No. (HC):</th>
                                            <td>{{$lawyer->bf_no_hc ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Registration/Ledger No. (LC):</th>
                                            <td>{{$lawyer->reg_no_lc ?? 'N/A'}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Uploads - Lower Court</legend>
                                <div class="row">
                                    <table class="table table-striped table-sm table-bordered">
                                        <tr>
                                            <th>2 Character Certificate:</th>
                                            <td>
                                                <img src="{{asset('storage/app/public/'.$lawyer->uploads->certificate_lc)}}"
                                                    class="custom-image-preview" alt="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Affidavit:</th>
                                            <td>
                                                <img src="{{asset('storage/app/public/'.$lawyer->uploads->affidavit_lc)}}"
                                                    class="custom-image-preview" alt="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>20 Case List (Image):</th>
                                            <td>
                                                <img src="{{asset('storage/app/public/'.$lawyer->uploads->cases_lc)}}"
                                                    class="custom-image-preview" alt="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Fee Voucher:</th>
                                            <td>
                                                <img src="{{asset('storage/app/public/'.$lawyer->uploads->voucher_lc)}}"
                                                    class="custom-image-preview" alt="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Law GAT Certificate:</th>
                                            <td>
                                                <img src="{{asset('storage/app/public/'.$lawyer->uploads->gat_lc)}}"
                                                    class="custom-image-preview" alt="">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Uploads High Court</legend>
                                <div class="row">
                                    <table class="table table-striped table-sm table-bordered">
                                        <tr>
                                            <th>2 Character Certificate:</th>
                                            <td>
                                                <img src="{{asset('storage/app/public/'.$lawyer->uploads->certificate_hc)}}"
                                                    class="custom-image-preview" alt="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Affidavit:</th>
                                            <td>
                                                <img src="{{asset('storage/app/public/'.$lawyer->uploads->affidavit_hc)}}"
                                                    class="custom-image-preview" alt="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>20 Case List (Image):</th>
                                            <td>
                                                <img src="{{asset('storage/app/public/'.$lawyer->uploads->cases_hc)}}"
                                                    class="custom-image-preview" alt="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Fee Voucher:</th>
                                            <td>
                                                <img src="{{asset('storage/app/public/'.$lawyer->uploads->voucher_hc)}}"
                                                    class="custom-image-preview" alt="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Law GAT Certificate:</th>
                                            <td>
                                                <img src="{{asset('storage/app/public/'.$lawyer->uploads->gat_hc)}}"
                                                    class="custom-image-preview" alt="">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right" value="save">Save &
                                Submit</button>
                            <a href="{{route('lawyers.create-step-7', $lawyer->id)}}"
                                class="btn btn-secondary float-right mr-1">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script src="{{asset('public/js/app.js')}}"></script>
<script>
    jQuery(document).ready(function () {
        App.init();
    });
    $(document).ready(function(){
      $("#create_step_8_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('lawyers.create-step-8', $lawyer->id)}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{route('lawyers.index')}}';
                }
              },
              error : function (errors) {
                alert('error')
              }
          });
      });
    });
</script>
@endsection
