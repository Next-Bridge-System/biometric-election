@extends('layouts.frontend')
@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('content')
@include('frontend.includes.slider')
@include('frontend.includes.buttons')

<div class="d-flex justify-content-center">
    <h4 class="text-center">Renewal Lower Court</h4>
</div>

<div class="container">
    <form action="#" method="POST" id="create_application_form" enctype="multipart/form-data"> @csrf
        <div class="card-body">

            <div class="bg-danger p-2 mb-3 hidden" id="error_message" role="alert">
                <span class="p-2"><i class="fas fa-exclamation-circle mr-1"></i>
                    One or more fields have an error. Please check and try again.</span>
            </div>

            <div class="bg-success p-2 mb-3 hidden" id="success_message" role="alert">
                <span class="p-2"><i class="fas fa-exclamation-circle mr-1"></i>
                    Thankyou your application has been submitted successfully</span>
            </div>

            <fieldset class="border p-4 mb-4">
                <legend class="w-auto">Personal & Contact Information</legend>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Advocate’s Name : <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="advocates_name">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Father Name: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="so_of">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Date of Birth: <span class="text-danger">*</span></label>
                        <div class="input-group date" id="date_of_birth" data-target-input="nearest">
                            <input type="text"
                                   value="{{isset($application->date_of_birth) ? $application->date_of_birth : ''}}"
                                   class="form-control datetimepicker-input" data-target="#date_of_birth"
                                   name="date_of_birth" required autocomplete="off"
                                   data-toggle="datetimepicker" />
                            <div class="input-group-append" data-target="#date_of_birth"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label>C.N.I.C. No: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="cnic_no">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Address: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="postal_address">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Phone No: <span class="text-danger">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><img src="{{asset('public/admin/images/pakistan.png')}}"
                                        alt=""></span>
                                <span class="input-group-text">+92</span>
                            </div>
                            <input type="tel" class="form-control" name="active_mobile_no">
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Email:</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                </div>
            </fieldset>

            <fieldset class="border p-4 mb-4">
                <legend class="w-auto">Legal Information</legend>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Date of Enrollment: <span class="text-danger">*</span></label>
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
                        </div>                    </div>
                    <div class="form-group col-md-6">
                        <label>Voter Member: <span class="text-danger">*</span></label>
                        <select name="voter_member_lc" id="voter_member_lc" class="form-control custom-select">
                            <option value="" selected>-- Select --</option>
                            @foreach ($bars as $bar)
                            <option value="{{$bar->id}}">{{$bar->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6 lower_court_section">
                        <label>Ledger No: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control lower_court_input" name="reg_no_lc">
                    </div>
                    <div class="form-group col-md-6">
                        <label>License No: <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="license_no_lc">
                    </div>
                </div>
            </fieldset>

            <fieldset class="border p-4 mb-4">
                <legend class="w-auto">Uploads & Attachments</legend>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Profile Picture: <span class="text-danger">*</span></label>
                        <input type="file" class="form-control custom-image-upload" name="profile_image_url"
                            accept="image/jpg,image/jpeg,image/png">
                    </div>
                    <div class="form-group col-md-6">
                        <label>CNIC (Front): <span class="text-danger">*</span></label>
                        <input type="file" class="form-control custom-image-upload" name="cnic_front_image_url"
                            accept="image/jpg,image/jpeg,image/png">
                    </div>
                    <div class="form-group col-md-6">
                        <label>CNIC (Back): <span class="text-danger">*</span></label>
                        <input type="file" class="form-control custom-image-upload" name="cnic_back_image_url"
                            accept="image/jpg,image/jpeg,image/png">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Punjab Bar Existing License (Front):</label>
                        <input type="file" class="form-control custom-image-upload" name="id_card_front_image_url"
                            accept="image/jpg,image/jpeg,image/png">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Punjab Bar Existing License (Back):</label>
                        <input type="file" class="form-control custom-image-upload" name="id_card_back_image_url"
                            accept="image/jpg,image/jpeg,image/png">
                    </div>
                </div>
            </fieldset>

            <fieldset class="border p-4 mb-4">
                <legend class="w-auto">Challan Form</legend>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Challan Form Number: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="voucher_no">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Challan Form Paid Date: <span class="text-danger">*</span></label>
                        <div class="input-group date" id="paid_date" data-target-input="nearest">
                            <input type="text"
                                   value="{{isset($application->paid_date) ? $application->paid_date : ''}}"
                                   class="form-control datetimepicker-input" data-target="#paid_date"
                                   name="paid_date" required autocomplete="off"
                                   data-toggle="datetimepicker" />
                            <div class="input-group-append" data-target="#paid_date"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Challan Form Image: <span class="text-danger">*</span></label>
                        <input type="file" class="form-control custom-image-upload" name="voucher_file">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Challan Form Type: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value="Renewal Lower Court" readonly>
                    </div>
                </div>
            </fieldset>

            <fieldset class="border p-4 mb-4">
                <legend class="w-auto">Instructions & Guidlines</legend>
                <div class="row">
                    <h6><b>Note:</b></h6>
                    <ol>
                        <li>User HBL voucher</li>
                        <li>User payment slip</li>
                        <li>Application with user and senior lawyer signatures</li>
                        <li>User all documents copies</li>
                        <li>User CNIC copy</li>
                        <li>Senior lawyer CNIC copy and licence copy submit to the relevant bar by user</li>
                    </ol>
                </div>
            </fieldset>

            <div class="form-group col-md-12 text-center">
                <button type="submit" class="btn btn-success mt-4" value="save">Submit
                    Application</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{asset('public/js/app.js')}}"></script>
<script>
    jQuery(document).ready(function () {
        App.init();
    });

    $('#date_of_birth').datetimepicker({
        size: 'large',
        format: 'DD-MM-YYYY',
    });

    $('#date_of_enrollment_lc').datetimepicker({
        size: 'large',
        format: 'DD-MM-YYYY',
    });

    $('#paid_date').datetimepicker({
        size: 'large',
        format: 'DD-MM-YYYY',
    });

    $(document).ready(function(){
      $("#create_application_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');

          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('frontend.renewal-lower-court')}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    var url = '{{ route("frontend.view-renewal-lower-court", ":id") }}';
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

</script>
@endsection
