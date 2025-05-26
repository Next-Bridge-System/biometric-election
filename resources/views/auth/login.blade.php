@extends('layouts.app')

@section('content')
<div class="container">
    <div class=" row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    @include('frontend.includes.logo')

                    <h2>Login</h2>
                    <h6>Login to your account</h6>
                </div>

                <div class="card-body">

                    <div class="col-md-10 offset-md-1">
                        <div class="alert alert-danger alert-dismissible fade show hidden errors text-center"
                            role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i><span id="error_message"></span>
                        </div>
                    </div>

                    <form method="POST" action="#" id="login_form"> @csrf

                        <div class="row">
                            <div class="col-md-12 text-center mb-4">
                                <input type="radio" id="phone" name="login_from" class="login_from" value="phone"
                                    checked>
                                <label class="mr-2" for="phone">Phone</label>
                                <input type="radio" id="email" name="login_from" class="login_from" value="email">
                                <label class="mr-2" for="email">Email</label>
                            </div>
                        </div>

                        <div class="form-group row" id="phone_section">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone')
                                }}</label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><img
                                                src="{{asset('public/admin/images/pakistan.png')}}" alt=""></span>
                                        <span class="input-group-text">+92</span>
                                    </div>
                                    <input type="number" class="form-control" name="phone">
                                </div>

                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row hidden" id="email_section">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address')
                                }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" autocomplete="email" autofocus
                                    placeholder="Email">

                                @error('email')
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
                                    autocomplete="current-password" placeholder="Password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-success login_btn">
                                    {{ __('Login') }}
                                </button>

                                <button class="btn btn-success hidden loading_btn" type="button" disabled><span
                                        class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                    Loading...</button>

                                @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    <b>{{ __('Forgot Your Password?') }}</b>
                                </a>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <div class="col-md-8 offset-md-4">
                                <a class="btn btn-link" href="{{ route('frontend.register') }}">
                                    <b>Don't have an account? Register</b>
                                </a>
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
        var value = $(this).val();
        if (value == 'phone') {
            $("#phone_section").removeClass('hidden');
            $("#email_section").addClass('hidden');
        } else {
            $("#phone_section").addClass('hidden');
            $("#email_section").removeClass('hidden');
        }
    });

    $(document).ready(function(){
      $("#login_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('frontend.login')}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".login_btn").addClass('hidden');
                $(".loading_btn").removeClass('hidden');
                $(".errors").addClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{route('frontend.otp')}}';
                }
              },
              error : function (errors) {
                errorsGet(errors.responseJSON.errors)
                if (errors.responseJSON.status == false) {
                    $(".errors").removeClass('hidden');
                    $("#error_message").text(errors.responseJSON.error);
                }
                $(".login_btn").removeClass('hidden');
                $(".loading_btn").addClass('hidden');
              }
          });
      });
    });
</script>

@endsection
