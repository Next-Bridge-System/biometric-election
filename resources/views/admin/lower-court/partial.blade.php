@extends('layouts.admin')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Lower Court Applications</h1>
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
                            Lower Court Partial List (Total Lower Court Partial : <span id="countTotal">0</span>)
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @include('admin.lower-court.partials.search-filters')

                        <div class="table-responsive">
                            <table id="applications_table"
                                class="table table-bordered table-striped table-sm text-center">
                                <thead>
                                    <tr>
                                        <th>Application No.</th>
                                        <th>User ID</th>
                                        <th>Lawyer Name</th>
                                        <th>CNIC No.</th>
                                        <th>Mobile No.</th>
                                        <th>Application Status</th>
                                        <th>Payment Status</th>
                                        <th>Submitted By</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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

@endsection

@section('scripts')
@include('admin.lower-court.scripts.lower-court-script')
@endsection
