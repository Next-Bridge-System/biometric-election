@extends('layouts.admin')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Vouchers</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Vouchers List (Total Vouchers : <span id="countTotal">0</span>)
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Lawyer Type</th>
                                        <th>Name</th>
                                        <th>Father</th>
                                        <th>DOB</th>
                                        <th>Station</th>
                                        <th>CNIC</th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th>Payment Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{asset('public/js/app.js')}}"></script>
<script>
    jQuery(document).ready(function () {
        App.init();
    });

    $(document).ready(function () {
        var table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            "responsive": true,
            "autoWidth": false,
            "searching": true,
            ajax: {
                url: "{{ route('vouchers.index') }}",
            },
            order:[[0,"desc"]],
            columns: [
                {data: 'id', name: 'id'},
                {data: 'application_type', name: 'application_type',orderable: false},
                {data: 'name', name: 'name',orderable: false},
                {data: 'father_name', name: 'father_name',orderable: false},
                {data: 'date_of_birth', name: 'date_of_birth',orderable: false},
                {data: 'station', name: 'station',orderable: false},
                {data: 'cnic_no', name: 'cnic_no',orderable: false},
                {data: 'email', name: 'email',orderable: false},
                {data: 'contact', name: 'contact',orderable: false},
                {data: 'payment_status', name: 'payment_status',orderable: false},
                {data: 'action', name: 'action',orderable: false},
            ],
            drawCallback: function (response) {
                $('#countTotal').empty();
                $('#countTotal').append(response['json'].recordsTotal);
            }
        });
    });
</script>
@endsection

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
