@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Register Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            {{-- <a href="{{route('users.index')}}" class="btn btn-dark btn-sm"><i
                            class="fas fa-chevron-left mr-2"></i>Back</a> --}}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Add User</h3>
                        </div>
                        <form id="create_form"> @csrf
                            <div class="card-body">
                                <fieldset class="border p-4 mb-2">
                                    <legend class="w-auto">Registration</legend>
                                    <div class="row">

                                        <div class="form-group col-md-6">
                                            <label>Name <span class="required-star">*</span></label>
                                            <input type="text" class="form-control" name="name" id="name"
                                                required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Father Name <span class="required-star">*</span></label>
                                            <input type="text" class="form-control" name="father_name" id="father_name"
                                                required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Email <span class="required-star">*</span></label>
                                            <input type="email" class="form-control" name="email" id="email"
                                                required>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label>Phone <span class="required-star">*</span></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><img
                                                            src="{{ asset('public/admin/images/pakistan.png') }}"
                                                            alt=""></span>
                                                    <span class="input-group-text">+92</span>
                                                </div>
                                                <input type="tel" id="phone" class="form-control" name="phone"
                                                    id="phone" required>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>CNIC No. <span class="required-star">*</span></label>
                                            <input type="text" maxlength="15" class="form-control" name="cnic_no"
                                                id="cnic_no" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Password <span class="required-star">*</span></label>
                                            <input type="password" class="form-control" name="password" id="password"
                                                required>
                                        </div>
                                        @if (Auth::guard('admin')->user()->id == 1)
                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                <label>Register Type<span class="required-star">*</span></label>
                                                <select name="register_as" id="register_as"
                                                    class="form-control custom-select">
                                                    <option value="intimation">Intimation</option>
                                                    <option value="lc">Lower Court</option>
                                                    <option value="hc">High Court</option>
                                                </select>
                                            </div>
                                        @endif
                                    </div>
                                </fieldset>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success float-right" id="submit_btn">
                                    Create</button><button class="btn btn-success float-right hidden" id="loading_btn"
                                    type="button" disabled><span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>Loading...</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('scripts')
    <script src="{{ asset('public/js/app.js') }}"></script>

    <script>
        jQuery(document).ready(function() {
            App.init();
        });

        $(document).ready(function() {
            $("#create_form").on("submit", function(event) {
                event.preventDefault();
                $('span.text-success').remove();
                $('span.invalid-feedback').remove();
                $('input.is-invalid').removeClass('is-invalid');
                var formData = new FormData(this);
                $.ajax({
                    method: "POST",
                    data: formData,
                    url: '{{ route('users.store') }}',
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function() {
                        $("#submit_btn").addClass("hidden");
                        $("#loading_btn").removeClass("hidden");
                    },
                    success: function(response) {
                        if (response.status == 1) {
                            $("#submit_btn").removeClass("hidden");
                            $("#loading_btn").addClass("hidden");
                            Swal.fire('Record Created!',
                                'The record has been created successfully.', 'success')
                            setTimeout(function() {
                                window.location.href =
                                    '{{ route('users.index', ['slug' => 'all']) }}';
                            }, 1000);
                        }
                    },
                    error: function(errors) {
                        errorsGet(errors.responseJSON.errors)
                        $("#submit_btn").removeClass('hidden');
                        $("#loading_btn").addClass('hidden');
                    }
                });
            });
        });
    </script>
@endsection
