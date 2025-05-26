@extends('layouts.admin')

@section('content')

<!-- Main content -->
<section class="content">
    <div class="container">
        <fieldset class="border p-4 mb-4">
            <legend class="w-auto">Biometric Verification</legend>
            <div class="row">
                <div class="col-md-3">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>100</h3>
                            <p class="text-capitalize">Total Users</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</section>

<div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form action="#" method="POST" id="change_password"> @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Update Password</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>New Password <span class="required-star">*</span></label>
                            <input type="password" class="form-control" name="password" placeholder="">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Confirm Password <span class="required-star">*</span></label>
                            <input type="password" class="form-control" name="password_confirmation" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success submit_btn">Update</button>
                    <button class="btn btn-success hidden loading_btn" type="button" disabled><span
                            class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        Loading...</button>
                </div>
        </form>
    </div>
</div>
</div>
@endsection

@section('scripts')

<script src="{{asset('public/js/app.js')}}"></script>
<script>
    jQuery(document).ready(function () {
        App.init();
    });
</script>

@if (Auth::guard('admin')->user()->default_password_changed == 0)
<script type="text/javascript">
    $(window).on('load', function() {
        $('#passwordModal').modal('show');
    });
</script>

<script>
    $(document).ready(function(){
      $("#change_password").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('admin.change-password')}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".submit_btn").addClass('hidden');
                $(".loading_btn").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    $('#passwordModal').modal('hide');
                    Swal.fire('Password Updated!',response.message,'success')
                }
              },
              error : function (errors) {
                errorsGet(errors.responseJSON.errors)
                $(".submit_btn").removeClass('hidden');
                $(".loading_btn").addClass('hidden');
              }
          });
      });
    });
</script>
@endif

@endsection