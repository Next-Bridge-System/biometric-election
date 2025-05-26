@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    @include('frontend.includes.logo')
                    <h2>
                        {{ __('Reset Password - OTP') }}
                    </h2>
                </div>

                <div class="card-body">

                    <div class="col-md-10 offset-md-1">
                        <div class="alert alert-danger alert-dismissible fade show hidden errors text-center"
                            role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i><span id="error_message"></span>
                        </div>
                    </div>

                    <form method="POST" action="#" id="otp_password_reset_form"> @csrf

                        <div class="form-group row">
                            <label for="otp" class="col-md-4 col-form-label text-md-right">{{ __('OTP')
                                }}</label>

                            <div class="col-md-6">
                                <input id="otp" type="otp" class="form-control @error('otp') is-invalid @enderror"
                                    name="otp" value="{{ $otp ?? old('otp') }}" required autocomplete="otp" autofocus>

                                @error('otp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password')
                                }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm
                                Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-success submit_btn">
                                    {{ __('Reset Password') }}
                                </button>

                                <button class="btn btn-success hidden loading_btn" type="button" disabled><span
                                        class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                    Loading...</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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

    $(document).ready(function(){
      $("#otp_password_reset_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('otpPasswordResetForm')}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".submit_btn").addClass('hidden');
                $(".loading_btn").removeClass('hidden');
                $(".errors").addClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{route('frontend.login')}}';
                }
              },
              error : function (errors) {
                errorsGet(errors.responseJSON.errors)
                if (errors.responseJSON.status == false) {
                    $(".errors").removeClass('hidden');
                    $("#error_message").text(errors.responseJSON.error);
                }
                $(".submit_btn").removeClass('hidden');
                $(".loading_btn").addClass('hidden');
              }
          });
      });
    });
</script>

@endsection
