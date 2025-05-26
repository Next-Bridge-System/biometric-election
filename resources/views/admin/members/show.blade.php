@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Members</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('members.index')}}" class="btn btn-dark">Back</a>
                    </li>
                </ol>
            </div>
        </div>
    </div><!-- /.container -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Member Detail
                        </h3>
                        <div class="card-tools">
                            @if(Auth::guard('admin')->user()->hasPermission('edit-members'))
                            <a href="{{ route('members.edit',$member->id) }}"><span class="badge badge-primary mr-1"><i
                                        class="fas fa-edit mr-1" aria-hidden="true"></i>Edit</span></a>
                            @endif
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="memberTable" class="table table-bordered table-striped table-sm">
                                <tr>
                                    <th>Member ID</th>
                                    <td>{{ $member->id }}</td>

                                    <th>Name</th>
                                    <td>{{ $member->name }}</td>
                                </tr>
                                <tr>
                                    <th>Father Name</th>
                                    <td>{{ $member->father_name }}</td>

                                    <th>Committee Name</th>
                                    <td>{{ $member->committee_name }}</td>
                                </tr>
                                <tr>
                                    <th>Mobile No</th>
                                    <td>{{ $member->mobile_no }}</td>

                                    <th>Division</th>
                                    <td>{{ $member->division->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>District</th>
                                    <td>{{ $member->district->name ?? 'N/A' }}</td>

                                    <th>Tehsil</th>
                                    <td>{{ $member->tehsil->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Bar</th>
                                    <td>{{ $member->bar->name ?? 'N/A' }}</td>

                                    <th>Address</th>
                                    <td>{{ $member->address }}</td>
                                </tr>
                                <tr>
                                    <th>Tenure Start Date</th>
                                    <td>{{ date('d-m-Y',strtotime($member->tenure_start_date)) }}</td>

                                    <th>Tenure End Date</th>
                                    <td>{{ date('d-m-Y',strtotime($member->tenure_end_date)) }}</td>
                                </tr>
                                <tr>
                                    <th>Signature Image</th>
                                    <td><img style="max-width:100px;height:auto" class="mt-2" src="{{ asset('storage/app/public/'.$member->signature_url) }}" alt="Signature Image"></td>

                                    <th>Designation</th>
                                    <td>{{ $member->designation ?? 'N/A' }}</td>
                                </tr>
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
