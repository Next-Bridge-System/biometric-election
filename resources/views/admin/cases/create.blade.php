@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>RFID Card Scan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    {{-- <li class="breadcrumb-item"><a href="{{route('cases.index')}}" class="btn btn-dark">Back</a>
                        --}}
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
                        <h3 class="card-title">Add Cases List</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class="card-body">
                        <div class="col-md-8 offset-md-2">
                            <div class="text-center">
                                <img src="{{asset('public/admin/images/rfid-card-scan.png')}}" alt="" class="w-25">
                            </div>
                            <div class="form-group">
                                <input type="text" name="rf_id" id="rf_id" class="form-control form-control-lg"
                                    placeholder="Scan RFID Card ..." autofocus autocomplete="off">
                            </div>
                            <span id="getErrorMessage" class="text-danger"></span>
                            <section class="hidden" id="getResults">
                                <div class="text-center">
                                    <span>Search Results for "<span class="text-lg" id="getRfId"></span>"</span>
                                </div>
                                <div class="text-center mt-4 mb-2">
                                    <span class="text-lg"><img src="" alt="" id="getProfileImage" class="w-25"><br>
                                        <span class="text-lg">Name: <span id="getName"></span></span> <br>
                                        <span class="text-lg">CNIC: <span id="getCnic"></span></span>
                                </div>
                            </section>
                        </div>

                        <form action="#" method="POST" id="store_cases_form"> @csrf
                            <fieldset class="border p-4 mb-4 hidden" id="case_form">
                                <legend class="w-auto">Cases Data</legend>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="">Case Title <span class="text-danger">*</span></label>
                                        <input type="text" name="case_title" id="case_title" class="form-control">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="">Case Type <span class="text-danger">*</span></label>
                                        <input type="text" name="case_type" id="case_type" class="form-control">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="">Judge Name <span class="text-danger">*</span></label>
                                        <input type="text" name="judge_name" id="judge_name" class="form-control">
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <button type="submit" class="btn btn-success float-right">Save & Submit</button>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="border p-4 mb-4 hidden" id="cases_list">
                                <legend class="w-auto">Cases List</legend>
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped table-bordered cases_list_table">
                                        </table>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
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
    $(function () {
        var table =  $(".cases_list_table").DataTable({
            "responsive": true,
            "autoWidth": false,
            "paginate": false,
        });
    });

    jQuery(document).ready(function () {
        App.init();
    });

    $(document).ready(function(){
        $("#rf_id").change(function (e) {
            var rf_id = $(this).val();
            $.ajax({
                method: "POST",
                url: '{{route('cases.create')}}',
                datatype: 'json',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    rf_id : rf_id
                },
                beforeSend: function(){
                    $(".custom-loader").removeClass('hidden');
                },
                success: function (response) {
                    if (response.status == 1) {
                        $("#rf_id").val('');
                        $(".custom-loader").addClass('hidden');
                        $("#getResults").removeClass('hidden');
                        $("#case_form").removeClass('hidden');
                        $("#cases_list").removeClass('hidden');
                        $("#getRfId").html(response.getRfId);
                        $("#getName").html(response.getName);
                        $("#getCnic").html(response.getCnic);
                        $('#getProfileImage').attr('src', response.getProfileImage);
                        $("#getErrorMessage").html('');
                        $(".cases_list_table").html(response.listView);
                        console.log(response.getProfileImage)
                    }
                },
                error : function (errors) {
                    errorsGet(errors.responseJSON.errors)
                    $(".custom-loader").addClass('hidden');
                    $("#getResults").addClass('hidden');
                    $("#case_form").addClass('hidden');
                    $("#cases_list").addClass('hidden');
                    $("#getErrorMessage").html(errors.responseJSON.errors);
                    $("#rf_id").val('');
                }
            });
        });

        $("#store_cases_form").on("submit", function(event){
            event.preventDefault();
            $('span.text-success').remove();
            $('span.invalid-feedback').remove();
            $('input.is-invalid').removeClass('is-invalid');
            var formData = new FormData(this);
            $.ajax({
                method: "POST",
                data: formData,
                url: '{{route('cases.store')}}',
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function(){
                    $(".custom-loader").removeClass('hidden');
                },
                success: function (response) {
                    if (response.status == 1) {
                        $(".custom-loader").addClass('hidden');
                        $("#rf_id").val('');
                        $('#store_cases_form').each(function(){
                            this.reset();
                        });
                        $(".cases_list_table").html(response.listView);
                        console.log(response.listView)
                        Swal.fire(
                            'Record Saved!',
                            'New record has been successfully saved.',
                            'success'
                            )
                    }
                },
                error : function (errors) {
                    errorsGet(errors.responseJSON.errors)
                    $(".custom-loader").addClass('hidden');
                    $("#error_message").removeClass('hidden');
                    $("#rf_id").val('');
                }
            });
        });

    });
</script>
@endsection
