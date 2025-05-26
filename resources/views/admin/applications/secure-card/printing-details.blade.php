@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{__('Secure Card Data')}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{route('applications.show', $application->id)}}" class="btn btn-sm btn-dark">Back</a>
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
                        <h3 class="card-title">Printing Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table class="table table-striped table-bordered">
                                @foreach ($application->prints as $print)
                                <tr>
                                    <th>{{$loop->iteration}}</th>
                                    <td>This application with token no
                                        <b>"{{$print->application->application_token_no}}"</b> has been
                                        added in queue at <b>"{{$print->created_at}}"</b>
                                        @if ($print->is_printed == 1)
                                        and printed at
                                        <b>"{{$print->printed_at}}"</b>
                                        @endif by operator
                                        <b>"{{getAdminName($print->admin_id)}}"</b>. The current status of
                                        secure card is <b>{{$print->card_status}}.</b>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
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
