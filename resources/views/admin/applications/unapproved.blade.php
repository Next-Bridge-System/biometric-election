@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{__('Secure Card Data')}}</h1>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-danger">
                    <div class="card-header">
                        <h3 class="card-title">
                            Unapproved List (Total Unapproved Applications: {{$applications->count()}})
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="applications_table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Application Token #</th>
                                        <th>Advocate's Name</th>
                                        <th>Father Name</th>
                                        <th>CNIC No.</th>
                                        <th>Mobile No.</th>
                                        <th>Application Type</th>
                                        <th>Application Status</th>
                                        <th>Card Status</th>
                                        <th>Address</th>
                                        <th>Submitted By</th>
                                        <th>Print Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($applications as $application)
                                    <tr>
                                        <td>{{$application->application_token_no}}</td>
                                        <td>{{$application->advocates_name}}</td>
                                        <td>{{$application->so_of}}</td>
                                        <td>{{$application->cnic_no}}</td>
                                        <td>{{$application->active_mobile_no}}</td>
                                        <td>
                                            @if ($application->application_type == 1) Lower Court
                                            @elseif($application->application_type == 2) High Court
                                            @elseif($application->application_type == 3) Renewal High Court
                                            @elseif($application->application_type == 3) Renewal Lower Court
                                            @endif
                                        </td>
                                        <td>
                                            @if ($application->application_status == 1) Active
                                            @elseif($application->application_status == 2) Suspended
                                            @elseif($application->application_status == 3) Died
                                            @elseif($application->application_status == 4) Removed
                                            @elseif($application->application_status == 5) Transfer in
                                            @elseif($application->application_status == 6) Transfer out
                                            @endif
                                        </td>
                                        <td>
                                            @if ($application->card_status == 1)
                                            <span class="badge badge-warning">Pending</span>
                                            @elseif($application->card_status == 2)
                                            <span class="badge badge-primary">Printing</span>
                                            @elseif($application->card_status == 3)
                                            <span class="badge badge-success">Dispatched</span>
                                            @elseif($application->card_status == 4)
                                            <span class="badge badge-success">By Hand</span>
                                            @elseif($application->card_status == 5)
                                            <span class="badge badge-success">Done</span>
                                            @endif
                                        </td>
                                        <td>{{$application->postal_address}} {{$application->address_2}}</td>
                                        <td>
                                            {{isset($application->submitted_by) ?
                                            getAdminName($application->submitted_by) : 'Online User'}}
                                        </td>
                                        <td>{{$application->print_date}}</td>
                                        <td>
                                            <form action="{{route('applications.unapproved')}}" method="POST"> @csrf
                                                <input type="hidden" name="application_id" id="application_id"
                                                    value="{{$application->id}}">
                                                <button type="submit" class="btn btn-success btn-xs"
                                                    onclick="return confirm('ARE YOU SURE ? YOU WANT TO APPROVE THIS APPLICATION.')">APPROVE
                                                    APPLICATION</button>
                                            </form>

                                            <a href={{route('applications.show',$application->id)}}>
                                                <span class="badge badge-primary mr-1 mr-1"><i class="fas fa-eye mr-1"
                                                        aria-hidden="true"></i>View Application</span></a>
                                        </td>
                                    </tr>
                                    @endforeach
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
<script>
    $(function () {
      $("#applications_table").DataTable({
        "responsive": true,
        "autoWidth": false,
      });
    });
</script>
@endsection
