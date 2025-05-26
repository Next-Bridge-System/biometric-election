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
                        <h3 class="card-title">Add Application</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="#" method="POST" id="create_application_form" enctype="multipart/form-data"> @csrf
                        <div class="card-body">
                            <div class="bg-danger p-2 mb-3 hidden" id="error_message" role="alert">
                                <span class="p-2"><i class="fas fa-exclamation-circle mr-1"></i>
                                    One or more fields have an error. Please check and try again.</span>
                            </div>

                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Application Information</legend>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Application Type<span class="text-danger">*</span>:</label>
                                        <select name="application_type" id="application_type"
                                            class="form-control custom-select" required>
                                            <option value="" selected>--Select Application Type--</option>
                                            <option value="1">Lower Court</option>
                                            <option value="2">High Court</option>
                                            <option value="3">Renewal High Court</option>
                                            <option value="4">Renewal Lower Court</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="border p-4 mb-4 hidden" id="application_form">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Advocate’s Name (As per Licence of
                                            Punjab Bar Council) :</label>
                                        <input type="text" class="form-control" name="advocates_name">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>S/o, D/o, W/o:</label>
                                        <input type="text" class="form-control" name="so_of">
                                    </div>
                                    <div class="form-group col-md-6 lower_court_section">
                                        <label>Registration / Ledger No :</label>
                                        <input type="text" class="form-control lower_court_input" name="reg_no_lc">
                                    </div>
                                    <div class="form-group col-md-6 lower_court_section">
                                        <label>License No (L.C.):</label>
                                        <input type="number" class="form-control lower_court_input"
                                            name="license_no_lc">
                                    </div>
                                    <div class="form-group col-md-6 high_court_section">
                                        <label>License No (H.C.):</label>
                                        <input type="number" class="form-control high_court_input" name="license_no_hc">
                                    </div>
                                    <div class="form-group col-md-6 renewal_high_court_section">
                                        <label>HRC No :</label>
                                        <input type="text" class="form-control renewal_high_court_input" name="hcr_no">
                                    </div>
                                    <div class="form-group col-md-6 high_court_section">
                                        <label>High Court Roll No : </label>
                                        <input type="number" class="form-control" name="high_court_roll_no">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>District :</label>
                                        <select name="district_id" id="district_id" class="form-control custom-select">
                                            <option value="" selected>--Select District--</option>
                                            @foreach ($districts as $district)
                                            <option value="{{$district->id}}">{{$district->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Tehsil :</label>
                                        <select name="tehsil_id" id="tehsil_id" class="form-control custom-select">
                                            <option value="" selected>--Select Tehsil--</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Date of Birth (As per Matriculation): <span
                                                class="text-danger">*</span></label>
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
                                    {{--<div class="form-group col-md-4">
                                        <label>Date of Birth (As per Matriculation):</label>
                                        <input type="date" class="form-control" name="date_of_birth">
                                    </div>--}}
                                    <div class="form-group col-md-4">
                                        <label>Date of Enrollment (L.C.):</label>
                                        {{--<input type="date" class="form-control" name="date_of_enrollment_lc">--}}
                                        <div class="input-group date" id="date_of_enrollment_lc"
                                            data-target-input="nearest">
                                            <input type="text"
                                                value="{{isset($application->date_of_enrollment_lc) ? $application->date_of_enrollment_lc : ''}}"
                                                class="form-control datetimepicker-input"
                                                data-target="#date_of_enrollment_lc" name="date_of_enrollment_lc"
                                                required autocomplete="off" data-toggle="datetimepicker" />
                                            <div class="input-group-append" data-target="#date_of_enrollment_lc"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group col-md-4 high_court_section">
                                        <label>Date of Enrollment (H.C.):</label>
                                        <div class="input-group date" id="date_of_enrollment_hc"
                                            data-target-input="nearest">
                                            <input type="text"
                                                value="{{isset($application->date_of_enrollment_hc) ? $application->date_of_enrollment_hc : ''}}"
                                                class="form-control datetimepicker-input"
                                                data-target="#date_of_enrollment_hc" name="date_of_enrollment_hc"
                                                required autocomplete="off" data-toggle="datetimepicker" />
                                            <div class="input-group-append" data-target="#date_of_enrollment_hc"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>C.N.I.C. No<span class="text-danger">*</span>:</label>
                                        <input type="text" class="form-control" name="cnic_no" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Postal Address:</label>
                                        <input type="text" class="form-control" name="postal_address">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Email:</label>
                                        <input type="email" class="form-control" name="email">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Whatsapp No:</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><img
                                                        src="{{asset('public/admin/images/pakistan.png')}}"
                                                        alt=""></span>
                                                <span class="input-group-text">+92</span>
                                            </div>
                                            <input type="tel" class="form-control" name="whatsapp_no">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Active Mobile No:</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><img
                                                        src="{{asset('public/admin/images/pakistan.png')}}"
                                                        alt=""></span>
                                                <span class="input-group-text">+92</span>
                                            </div>
                                            <input type="tel" class="form-control" name="active_mobile_no">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Voter Member (L.C.):</label>
                                        <input type="text" class="form-control" name="voter_member_lc">
                                    </div>
                                    <div class="form-group col-md-6 high_court_section">
                                        <label>Voter Member (H.C.):</label>
                                        <input type="text" class="form-control high_court_input" name="voter_member_hc">
                                    </div>
                                    <div class="form-group col-md-6 lower_court_section">
                                        <label>B.F No (L.C.):</label>
                                        <input type="number" class="form-control lower_court_input" name="bf_no_lc">
                                    </div>
                                    <div class="form-group col-md-6 high_court_section">
                                        <label>B.F No (H.C.):</label>
                                        <input type="number" class="form-control high_court_input" name="bf_no_hc">
                                    </div>
                                    <div class="form-group col-md-6 lower_court_section">
                                        <label>R.F ID:</label>
                                        <input type="number" class="form-control" name="rf_id">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Upload Profile Picture: 
                                        </label>
                                        <input type="file" class="form-control custom-image-upload"
                                            name="profile_image_url" accept="image/jpg,image/jpeg,image/png">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Upload CNIC (Front):</label>
                                        <input type="file" class="form-control custom-image-upload"
                                            name="cnic_front_image_url" accept="image/jpg,image/jpeg,image/png">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Upload CNIC (Back):</label>
                                        <input type="file" class="form-control custom-image-upload"
                                            name="cnic_back_image_url" accept="image/jpg,image/jpeg,image/png">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>ID Card issued by the Pb.B.C (Front):</label>
                                        <input type="file" class="form-control custom-image-upload"
                                            name="id_card_front_image_url" accept="image/jpg,image/jpeg,image/png">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>ID Card issued by the Pb.B.C (Back):</label>
                                        <input type="file" class="form-control custom-image-upload"
                                            name="id_card_back_image_url" accept="image/jpg,image/jpeg,image/png">
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right" value="save">Save & Next</button>
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
      $("#create_application_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var submitButtonType = $("button[type=submit]:focus").val();
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('applications.store')}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    var url = '{{ route("applications.preview", ":id") }}';
                    url = url.replace(':id', response.application);
                    window.location.href = url;
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

    $("#application_type").change(function (e) {
        e.preventDefault();
        var type = $(this).val();
        if (type == 1 || type == 2 || type == 3 || type == 4) {
            $("#application_form").removeClass('hidden');

            if (type == 1 || type == 4) {
                $(".lower_court_section").removeClass('hidden');
                $('.lower_court_input').attr('disabled', false);
                $(".high_court_section").addClass('hidden');
                $('.high_court_input').attr('disabled', true);
                // $('.high_court_input').prop('required',false);
                // $('.lower_court_input').prop('required',true);
                $(".renewal_high_court_section").addClass('hidden');
                $('.renewal_high_court_input').attr('disabled', true);
            }

            if (type == 2) {
                $(".lower_court_section").addClass('hidden');
                $('.lower_court_input').attr('disabled', true);
                $(".high_court_section").removeClass('hidden');
                $('.high_court_input').attr('disabled', false);
                $(".renewal_high_court_section").addClass('hidden');
                $('.renewal_high_court_input').attr('disabled', true);
            }

            if (type == 3) {
                $(".lower_court_section").addClass('hidden');
                $('.lower_court_input').attr('disabled', true);
                $(".high_court_section").removeClass('hidden');
                $('.high_court_input').attr('disabled', false);
                $(".renewal_high_court_section").removeClass('hidden');
                $('.renewal_high_court_input').attr('disabled', false);
            }

        } else {
            $("#application_form").addClass('hidden');
        }
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