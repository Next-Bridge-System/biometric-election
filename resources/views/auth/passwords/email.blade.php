@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    @include('frontend.includes.logo')
                    <h2> {{ __('Reset Password') }} </h2>
                    <h6>(Get Verification Link - Email)</h6>
                </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12 text-center mb-4">
                            <input type="radio" id="phone" name="login_from" class="login_from" value="phone">
                            <label class="mr-2" for="phone">Phone</label>
                            <input type="radio" id="email" name="login_from" class="login_from" value="email" checked>
                            <label class="mr-2" for="email">Email</label>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address')
                                }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Send Password Reset Link') }}
                                </button>
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
<script>
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
</script>
@endsection
