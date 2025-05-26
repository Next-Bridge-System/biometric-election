@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    @include('frontend.includes.logo')
                    <h2> {{ __('Reset Password') }} </h2>
                    <h6>(Get Verification OTP - Phone)</h6>
                </div>

                <div class="card-body">

                    <div class="col-md-10 offset-md-1">
                        <div class="alert alert-danger alert-dismissible fade show hidden errors text-center"
                            role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i><span id="error_message"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center mb-4">
                            <input type="radio" id="phone" name="login_from" class="login_from" value="phone" checked>
                            <label class="mr-2" for="phone">Phone</label>
                            <input type="radio" id="email" name="login_from" class="login_from" value="email">
                            <label class="mr-2" for="email">Email</label>
                        </div>
                    </div>

                    <form method="POST" action="#" id="otp_password_reset_form"> @csrf

                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number')
                                }}</label>

                            <div class="col-md-6">

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><img
                                                src="{{asset('public/admin/images/pakistan.png')}}" alt=""></span>
                                        <span class="input-group-text">+92</span>
                                    </div>
                                    <input id="phone" type="phone"
                                        class="form-control @error('phone') is-invalid @enderror" name="phone"
                                        value="{{ old('phone') }}" required autocomplete="phone" autofocus>
                                </div>

                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-success submit_btn">
                                    {{ __('Send OTP') }}
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

    $(".login_from").change(function (e) {
        e.preventDefault();
        $(".custom-loader").removeClass('hidden');
        var value = $(this).val();
        if (value == 'phone') {
        var url = '{{ route("otpPasswordReset") }}';
        window.location.href = url;
        } else {
        var url = '{{ route("password.request") }}';
        window.location.href = url;
        }
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
              url: '{{route('otpPasswordReset')}}',
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
                    window.location.href = '{{route('otpPasswordResetForm')}}';
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
