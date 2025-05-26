@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Universities</h1>
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
                        <h3 class="card-title">Show University</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table class="table table-striped table-bordered table-sm">
                                <tr>
                                    <th>University Name:</th>
                                    <td>{{$university->name}}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{$university->phone}}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{$university->email}}</td>
                                </tr>
                                <tr>
                                    <th>Country:</th>
                                    <td>{{$university->country->name ?? '-'}}</td>
                                </tr>
                                <tr>
                                    <th>Province:</th>
                                    <td>{{$university->province->name ?? '-'}}</td>
                                </tr>
                                <tr>
                                    <th>District:</th>
                                    <td>{{$university->district->name ?? '-'}}</td>
                                </tr>
                                <tr>
                                    <th>City:</th>
                                    <td>{{$university->city}}</td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td>{{$university->address}}</td>
                                </tr>
                                <tr>
                                    <th>Postal Code:</th>
                                    <td>{{$university->postal_code}}</td>
                                </tr>
                                <tr>
                                    <th>University Added By</th>
                                    <td>
                                        @if (isset($university->user_id))
                                        <b>Username: </b> {{isset($university->user_id) ? $university->user->name:
                                        ''}} - {{$university->user_id}}
                                        @else N/A @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Assigned To:</th>
                                    <th>
                                        @if ($university->educations->count() > 0)
                                        Applications:
                                        @foreach ($university->educations as $ue)
                                        <span
                                            class="badge badge-primary">{{isset($ue->university->application->application_token_no)
                                            ?
                                            $ue->university->application->application_token_no : ''}}</span>
                                        @endforeach
                                        @endif
                                    </th>
                                </tr>
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
