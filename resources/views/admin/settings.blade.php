@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Password & Security</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{url()->previous()}}" class="btn btn-dark">Back</a>
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
                        <h3 class="card-title">Reset Password</h3>
                    </div>
                    <!-- /.card-header -->

                    @if (Session::has('error_message'))
                    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                        {{Session::get('error_message')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    @if (Session::has('success_message'))
                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                        {{Session::get('success_message')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    <!-- form start -->
                    <form action="{{route('admin.update-password')}}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Current Password <span class="required-star">*</span></label>
                                    <div class="input-group mb-3">
                                        <input type="password" class="form-control" name="current_password"
                                            id="current_password" placeholder="Enter Current Password" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text toggle-password" toggle="#current_password"><i
                                                    class="fa fa-eye"></i></span>
                                        </div>
                                    </div>

                                    <span id="check_current_password"></span>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>New Password <span class="required-star">*</span></label>
                                    <div class="input-group mb-3">
                                        <input type="password" class="form-control" name="new_password"
                                            id="new_password" placeholder="Enter New Password" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text toggle-password" toggle="#new_password"><i
                                                    class="fa fa-eye"></i></span>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group col-md-4">
                                    <label>Confirm Password <span class="required-star">*</span></label>
                                    <div class="input-group mb-3">
                                        <input type="password" class="form-control" name="confirm_password"
                                            id="confirm_password" placeholder="Enter Confirm Password" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text toggle-password" toggle="#confirm_password"><i
                                                    class="fa fa-eye"></i></span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
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
<script>
    $(document).ready(function () {
    $('#current_password').keyup(function (e) {
        var current_password = $('#current_password').val();
        $.ajax({
                method: "Post",
                url: '{{route('admin.check-password')}}',
                dataType:'html',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'current_password': current_password,
                },
                success: function (response) {
                    if(response == "false"){
                        $("#check_current_password").html("<font color=red>Current Password is Incorrect</font>");
                    }
                    else if (response == "true"){
                        $("#check_current_password").html("<font color=green>Current Password is Correct</font>");
                    }
                    // console.log(response);
                }
            });
    });
});

    $(".toggle-password").click(function() {
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
</script>
@endsection
