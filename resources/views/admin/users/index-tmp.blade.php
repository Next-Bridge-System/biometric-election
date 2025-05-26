@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Register Users</h1>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Register Users List (Total Register Users : <span id="countTotal">0</span>)
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered data-table table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>NAME</th>
                                        <th>EMAIL</th>
                                        <th>MOBILE</th>
                                        <th>CNIC NO</th>
                                        <th>TYPE</th>
                                        <th>OTP</th>
                                        <th>MBL VER AT</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection

@section('scripts')

<script type="text/javascript">
    $(function () {
      var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        "responsive": true,
        "autoWidth": false,
          ajax: "{{ route('users.index') }}",
          columns: [
              {data: 'id', name: 'id'},
              {data: 'name', name: 'name'},
              {data: 'email', name: 'email'},
              {data: 'phone', name: 'phone'},
              {data: 'cnic_no', name: 'cnic_no'},
              {data: 'register_as', name: 'register_as'},
              {data: 'otp', name: 'otp'},
              {data: 'phone_verified_at', name: 'phone_verified_at'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ],
        //   order:[[0,"desc"]],
          drawCallback: function (response) {
                $('#countTotal').empty();
                $('#countTotal').append(response['json'].recordsTotal);
            }
      });

    });
</script>

<script>
    function status(row_id,status) {
        $.ajax({
            method: "POST",
            url: '{{route('users.status')}}',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                'row_id': row_id,
                'status': status,
            },
            success: function (response) {
                if (response.status == 1) {
                    $("#status_"+row_id).addClass("hidden");
                    $("#loading_btn_"+row_id).removeClass("hidden");
                    setTimeout(function () {
                        toastr.success('Status Updated.');
                        $('#datatable').DataTable().ajax.reload();
                    }, 500);
                }
            },
        });
    }
</script>

@endsection
