@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    @include('frontend.includes.logo')
                    <h2>Verify Your Account</h2>
                    <h5>Please enter code we sent to your mobile number +923******{{substr($user->phone, -3)}}</h5>
                </div>
                <div class="card-body">

                    <form method="POST" action="#" id="resend_otp"> @csrf
                        {{ __('Before proceeding, please check your mobile or email for a verification code.') }}
                        {{ __('If you did not receive the code') }},
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline text-success"><b>{{ __('click
                                here
                                to request another') }}</b></button>.
                    </form>

                    <div class="col-md-6 offset-md-3 mt-4">
                        <form method="POST" action="{{route('frontend.otp')}}"> @csrf
                            <input type="text" name="otp" id="otp" class="form-control"
                                placeholder="Enter 6 digit OTP code." required>
                            <button type="submit" class="btn btn-success btn-sm mt-2">Submit OTP</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

@if (session('error'))
<script>
    notifyToast('error',' {{ session('error') }}');
</script>
@endif

<script src="{{asset('public/js/app.js')}}"></script>

<script>
    jQuery(document).ready(function () {
        App.init();
    });

    $(document).ready(function(){
      $("#resend_otp").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('frontend.resend-otp')}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                Swal.fire('OTP Resend Successfully!','A fresh verification code has been sent to your mobile number & email address.','success');
                $(".custom-loader").addClass('hidden');
              },
              error : function (errors) {
                $(".custom-loader").addClass('hidden');
              }
          });
      });
    });
</script>

@endsection
