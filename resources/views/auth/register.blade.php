@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    @include('frontend.includes.logo')
                    <h2>Register</h2>
                    <h6>Lets get you on board</h6>
                </div>

                <div class="card-body">
                    <form method="POST" action="#" id="register_form"> @csrf
                        <div class="row">
                            {{-- <div class="form-group col-md-3">
                                <label>First Name <span class="text-danger">*</span></label>
                                <input id="fname" type="text" class="form-control @error('fname') is-invalid @enderror"
                                    name="fname" value="{{ old('fname') }}" required autocomplete="fname" autofocus>
                                @error('fname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div> --}}

                            {{-- <div class="form-group col-md-3">
                                <label>Last Name <span class="text-danger">*</span></label>
                                <input id="lname" type="text" class="form-control @error('lname') is-invalid @enderror"
                                    name="lname" value="{{ old('lname') }}" required autocomplete="lname">
                                @error('lname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div> --}}

                            <div class="form-group col-md-6">
                                <label>Lawyer Name <span class="text-danger">*</span></label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required autocomplete="name">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>Father/Husband <span class="text-danger">*</span></label>
                                <input id="father_name" type="text" class="form-control @error('father_name') is-invalid @enderror"
                                    name="father_name" value="{{ old('father_name') }}" required autocomplete="father_name">
                                @error('father_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            
                            <div class="col-md-6 form-group">
                                <label>{{ __('E-Mail Address')
                                    }} <span class="text-danger">*</span></label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-3 form-group">
                                <label>{{ __('Phone')}} <span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        {{-- <span class="input-group-text"><img
                                                src="{{asset('public/admin/images/pakistan.png')}}" alt=""></span> --}}
                                        <span class="input-group-text">+92</span>
                                    </div>
                                    <input type="tel" class="form-control" name="phone" required>
                                </div>
                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>


                            <div class="form-group col-md-3">
                                <label>C.N.I.C. No <span class="text-danger">*</span>:</label>
                                <input type="text" class="form-control" name="cnic_no" required>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>{{ __('Password')}} <span class="text-danger">*</span></label>

                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label>{{ __('Confirm Password') }} <span class="text-danger">*</span></label>

                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        {{-- <div class="row">
                            <div class="col-md-12 text-center mt-3 mb-3">
                                <input @if (isset(Route::current()->parameters() ['type']) &&
                                Route::current()->parameters() ['type'] == 'intimation')
                                checked @endif type="radio" id="intimation" name="register_as" class="register_as"
                                value="intimation">
                                <label class="mr-2" for="intimation">Intimation/1<sup>st</sup> Intimation</label>
                                <input @if (isset(Route::current()->parameters() ['type']) &&
                                Route::current()->parameters() ['type'] == 'lc')
                                checked @endif type="radio" id="lc" name="register_as" class="register_as" value="lc">
                                <label class="mr-2" for="lc">Lower Court/2<sup>nd</sup> Intimation</label>
                            </div>
                        </div> --}}

                        <div class="form-group row mt-4">
                            <div class="col-md-12">
                                <div class="col-md-6 offset-md-3">
                                    <button type="submit" class="btn btn-success btn-block register_btn">
                                        Register
                                    </button>
                                    <button class="btn btn-success btn-block hidden loading_btn" type="button"
                                        disabled><span class="spinner-grow spinner-grow-sm" role="status"
                                            aria-hidden="true"></span>
                                        Loading...</button>
                                    <a class="btn btn-link btn-block" href="{{ route('frontend.login') }}">
                                        <b>Already have an account? Login</b>
                                    </a>
                                </div>
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
      $("#register_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('frontend.register')}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".register_btn").addClass('hidden');
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
                $(".register_btn").removeClass('hidden');
                $(".loading_btn").addClass('hidden');
              }
          });
      });
    });
</script>

@endsection