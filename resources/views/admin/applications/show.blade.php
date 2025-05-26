@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-4">
                <h1>{{__('Secure Card Data')}}</h1>
            </div>
            <div class="col-sm-8">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{route('applications.index')}}" class="btn btn-sm btn-dark">Back</a>
                        <a href="{{route('secure-card.printing-details', $application->id)}}"
                            class="btn btn-sm btn-primary">Printing Details</a>

                        @if (Auth::guard('admin')->user()->hasPermission('manage-biometric-verification'))
                        <a href="{{route('biometrics.show', $application->id)}}" class="btn btn-sm btn-info">Biometric
                            Verification</a>
                        @endif

                        @include('admin.applications.secure-card.vpp-return-back-status')
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
                        <h3 class="card-title">Show Application</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @include('admin.applications._application-detail')
                        </div>
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
