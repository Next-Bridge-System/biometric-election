@extends('layouts.admin')
@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{__('Secure Card Data')}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('applications.index')}}" class="btn btn-dark">Back</a>
                    </li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Edit Application</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="#" method="POST" id="edit_application_form" enctype="multipart/form-data"> @csrf
                        <div class="card-body">
                            <div class="bg-danger p-2 mb-3 hidden" id="error_message" role="alert">
                                <span class="p-2"><i class="fas fa-exclamation-circle mr-1"></i>
                                    One or more fields have an error. Please check and try again.</span>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>Application Token #</label>
                                    <input type="text" class="form-control"
                                        value="{{$application->application_token_no}}" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Application Type:</label>
                                    <input type="text" name="application_type" class="form-control" value="<?php
                                        if ($application->application_type == 1) echo 'Lower Court';
                                        else if ($application->application_type == 2) echo 'High Court';
                                        else if ($application->application_type == 3) echo 'Renewal High Court';
                                        else if ($application->application_type == 4) echo 'Renewal Lower Court';
                                        ?>" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Application Status<span class="text-danger">*</span>:</label>
                                    <select name="application_status" id="application_status"
                                        class="form-control custom-select" required>
                                        <option value="" selected>--Select Application Status--</option>
                                        <option value="1" {{$application->application_status == 1 ? 'selected' : ''}}>
                                            Active</option>
                                        <option value="2" {{$application->application_status == 2 ? 'selected' : ''}}>
                                            Suspended</option>
                                        <option value="3" {{$application->application_status == 3 ? 'selected' : ''}}>
                                            Died</option>
                                        <option value="4" {{$application->application_status == 4 ? 'selected' : ''}}>
                                            Removed</option>
                                        <option value="5" {{$application->application_status == 5 ? 'selected' : ''}}>
                                            Transfer in</option>
                                        <option value="6" {{$application->application_status == 6 ? 'selected' : ''}}>
                                            Transfer out</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Card Status<span class="text-danger">*</span>:</label>
                                    <select name="card_status" id="card_status" class="form-control custom-select"
                                        required>
                                        <option value="1" {{$application->card_status == 1 ? 'selected' : ''}}>
                                            Pending</option>
                                        <option value="2" {{$application->card_status == 2 ? 'selected' : ''}}>
                                            Printing</option>
                                        <option value="3" {{$application->card_status == 3 ? 'selected' : ''}}>
                                            Dispatched</option>
                                        <option value="4" {{$application->card_status == 4 ? 'selected' : ''}}>
                                            By Hand</option>
                                        <option value="5" {{$application->card_status == 5 ? 'selected' : ''}}>
                                            Done</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Advocate’s Name (As per Licence of
                                        Punjab Bar Council) :</label>
                                    <input type="text" class="form-control" name="advocates_name"
                                        value="{{$application->advocates_name}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>S/o, D/o, W/o:</label>
                                    <input type="text" class="form-control" name="so_of"
                                        value="{{$application->so_of}}">
                                </div>
                                @if ($application->application_type == 1)
                                <div class="form-group col-md-6">
                                    <label>Registration / Ledger No : </label>
                                    <input type="text" class="form-control" name="reg_no_lc"
                                        value="{{$application->reg_no_lc}}">
                                </div>
                                @endif

                                @if ($application->application_type == 1)
                                <div class="form-group col-md-6">
                                    <label>License No (L.C.):</label>
                                    <input type="number" class="form-control" name="license_no_lc"
                                        value="{{$application->license_no_lc}}">
                                </div>
                                @endif

                                @if ($application->application_type == 2)
                                <div class="form-group col-md-6">
                                    <label>License No (H.C.):</label>
                                    <input type="number" class="form-control" name="license_no_hc"
                                        value="{{$application->license_no}}">
                                </div>
                                @endif

                                @if ($application->application_type == 3)
                                <div class="form-group col-md-6">
                                    <label>HRC No :</label>
                                    <input type="text" class="form-control" name="hcr_no"
                                        value="{{$application->hcr_no}}">
                                </div>
                                @endif

                                @if ($application->application_type == 2)
                                <div class="form-group col-md-6">
                                    <label>High Court Roll No :</label>
                                    <input type="number" class="form-control" name="high_court_roll_no"
                                        value="{{$application->high_court_roll_no}}">
                                </div>
                                @endif
                                <div class="form-group col-md-6">
                                    <label>District :</label>
                                    <select name="district_id" id="district_id" class="form-control custom-select">
                                        <option value="" selected>--Select District--</option>
                                        @foreach ($districts as $district)
                                        <option {{$district->id == $application->district_id ? 'selected' : ''}}
                                            value="{{$district->id}}">{{$district->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Tehsil :</label>
                                    <select name="tehsil_id" id="tehsil_id" class="form-control custom-select">
                                        <option value="" selected>--Select Tehsil--</option>
                                        @foreach ($tehsils->where('district_id', $application->district_id) as $tehsil)
                                        <option {{$tehsil->id == $application->tehsil_id ? 'selected' : ''}}
                                            value="{{$tehsil->id}}">{{$tehsil->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Date of Birth (As per
                                        Matriculation):</label>
                                    <div class="input-group date" id="dateOfBirth" data-target-input="nearest">
                                        <input type="text"
                                               value="{{isset($application->date_of_birth) ? $application->date_of_birth : ''}}"
                                               class="form-control datetimepicker-input" data-target="#dateOfBirth"
                                               name="date_of_birth" required autocomplete="off"
                                               data-toggle="datetimepicker" />
                                        <div class="input-group-append" data-target="#dateOfBirth"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Date of Enrollment (L.C.):</label>
                                    <div class="input-group date" id="date_of_enrollment_lc" data-target-input="nearest">
                                        <input type="text"
                                               value="{{isset($application->date_of_enrollment_lc) ? $application->date_of_enrollment_lc : ''}}"
                                               class="form-control datetimepicker-input" data-target="#date_of_enrollment_lc"
                                               name="date_of_enrollment_lc" required autocomplete="off"
                                               data-toggle="datetimepicker" />
                                        <div class="input-group-append" data-target="#date_of_enrollment_lc"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                @if ($application->application_type == 2)
                                <div class="form-group col-md-4">
                                    <label>Date of Enrollment (H.C.):</label>
                                    <div class="input-group date" id="date_of_enrollment_hc" data-target-input="nearest">
                                        <input type="text"
                                               value="{{isset($application->date_of_enrollment_hc) ? $application->date_of_enrollment_hc : ''}}"
                                               class="form-control datetimepicker-input" data-target="#date_of_enrollment_hc"
                                               name="date_of_enrollment_hc" required autocomplete="off"
                                               data-toggle="datetimepicker" />
                                        <div class="input-group-append" data-target="#date_of_enrollment_hc"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group col-md-6">
                                    <label>C.N.I.C. No<span class="text-danger">*</span>:</label>
                                    <input type="text" class="form-control" name="cnic_no"
                                        value="{{$application->cnic_no}}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>
                                        Postal Address:</label>
                                    <input type="text" class="form-control" name="postal_address"
                                        value="{{$application->postal_address}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email:</label>
                                    <input type="text" class="form-control" name="email"
                                        value="{{$application->email}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Whatsapp No:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><img
                                                    src="{{asset('public/admin/images/pakistan.png')}}" alt=""></span>
                                            <span class="input-group-text">+92</span>
                                        </div>
                                        <input type="tel" class="form-control" name="whatsapp_no"
                                            value="{{$application->whatsapp_no}}">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Active Mobile No:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><img
                                                    src="{{asset('public/admin/images/pakistan.png')}}" alt=""></span>
                                            <span class="input-group-text">+92</span>
                                        </div>
                                        <input type="tel" class="form-control" name="active_mobile_no"
                                            value="{{$application->active_mobile_no}}">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Voter Member (L.C.):</label>
                                    <input type="text" class="form-control" name="voter_member_lc"
                                        value="{{$application->voter_member_lc}}">
                                </div>
                                @if ($application->application_type == 2)
                                <div class="form-group col-md-6">
                                    <label>Voter Member (H.C.):</label>
                                    <input type="text" class="form-control" name="voter_member_hc"
                                        value="{{$application->voter_member_hc}}">
                                </div>
                                @endif
                                @if ($application->application_type == 1)
                                <div class="form-group col-md-6">
                                    <label>R.F ID:</label>
                                    <input type="number" class="form-control" name="rf_id"
                                        value="{{$application->rf_id}}">
                                </div>
                                @endif

                                @if ($application->application_type == 1)
                                <div class="form-group col-md-6">
                                    <label>B.F No (L.C.):</label>
                                    <input type="number" class="form-control" name="bf_no_lc"
                                        value="{{$application->bf_no_lc}}">
                                </div>
                                @endif

                                @if ($application->application_type == 2)
                                <div class="form-group col-md-6">
                                    <label>B.F No (H.C.):</label>
                                    <input type="number" class="form-control" name="bf_no_hc"
                                        value="{{$application->bf_no_hc}}">
                                </div>
                                @endif

                                <div class="form-group col-md-6">
                                    <label>Upload Profile Picture:</label>
                                    <input type="file" class="form-control custom-image-upload" name="profile_image_url"
                                        accept="image/jpg,image/jpeg,image/png">
                                    @if (isset($application->profile_image_url))
                                    <img src="{{asset('storage/app/public/'.$application->profile_image_url)}}" alt=""
                                        class="custom-image-preview">
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Upload CNIC (Front):</label>
                                    <input type="file" class="form-control custom-image-upload"
                                        name="cnic_front_image_url" accept="image/jpg,image/jpeg,image/png">
                                    @if (isset($application->uploads->cnic_front))
                                    <img src="{{asset('storage/app/public/'.$application->uploads->cnic_front)}}" alt=""
                                        class="custom-image-preview">
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Upload CNIC (Back):</label>
                                    <input type="file" class="form-control custom-image-upload"
                                        name="cnic_back_image_url" accept="image/jpg,image/jpeg,image/png">
                                    @if (isset($application->uploads->cnic_back))
                                    <img src="{{asset('storage/app/public/'.$application->uploads->cnic_back)}}" alt=""
                                        class="custom-image-preview">
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <label>ID Card issued by the Pb.B.C (Front):</label>
                                    <input type="file" class="form-control custom-image-upload"
                                        name="id_card_front_image_url" accept="image/jpg,image/jpeg,image/png">
                                    @if (isset($application->uploads->card_front))
                                    <img src="{{asset('storage/app/public/'.$application->uploads->card_front)}}" alt=""
                                        class="custom-image-preview">
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <label>ID Card issued by the Pb.B.C (Back):</label>
                                    <input type="file" class="form-control custom-image-upload"
                                        name="id_card_back_image_url" accept="image/jpg,image/jpeg,image/png">
                                    @if (isset($application->uploads->card_back))
                                    <img src="{{asset('storage/app/public/'.$application->uploads->card_back)}}" alt=""
                                        class="custom-image-preview">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Save & Update</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
            <!-- right column -->
            <div class="col-md-6">

            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection


@section('scripts')
<script src="{{asset('public/js/app.js')}}"></script>
<script>
    jQuery(document).ready(function () {
        App.init();
    });

    $('#dateOfBirth').datetimepicker({
        size: 'large',
        format: 'DD-MM-YYYY',
    });

    $('#date_of_enrollment_lc').datetimepicker({
        size: 'large',
        format: 'DD-MM-YYYY',
    });

    $('#date_of_enrollment_hc').datetimepicker({
        size: 'large',
        format: 'DD-MM-YYYY',
    });

    $(document).ready(function(){
      $("#edit_application_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('applications.update',$application->id)}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{ route("applications.index") }}';
                }
              },
              error : function (errors) {
                $('html, body').animate({scrollTop:$('#error_message').position().top}, 'slow');
                errorsGet(errors.responseJSON.errors)
                $(".custom-loader").addClass('hidden');
                $("#error_message").removeClass('hidden');
              }
          });
      });
    });

    $('#district_id').on('change', function () {
        var district_id = $('#district_id').find(":selected").val();
        var option = '';
        $.ajax({
            method: "POST",
            url: '{{route('getTehsilsByDistrict')}}',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                'district_id': district_id
            },
            success: function (response) {
                $('#tehsil_id').empty();
                $('#tehsil_id').append(' <option value="" selected>--Select Tehsil--</option>');
                response.tehsils.forEach(function (item, index) {
                    option = "<option value='" + item.id + "'>" + item.name + "</option>"
                    $('#tehsil_id').append(option);
                });
            }
        });
    });
</script>
@endsection
