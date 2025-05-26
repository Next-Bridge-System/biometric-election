@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Universities</h1>
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
                            Unapproved Universities List (Total Unapproved Universities : {{$unapprovedUniversities->count()}})
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="universities_table" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th style="width:20px">#</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Country</th>
                                    <th>Address</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($unapprovedUniversities as $university)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$university->name}}</td>
                                    <td>{{$university->phone}}</td>
                                    <td>{{$university->email}}</td>
                                    <td>{{$university->country->name ?? '-'}}</td>
                                    <td>{{$university->address}}</td>
                                    <td>{{$university->type == 1 ? 'University' : 'Affliated'}}</td>
                                    <td>
                                        @if (Auth::guard('admin')->user()->hasPermission('edit-universities'))
                                        <a href="{{route('universities.edit',$university->id)}}">
                                            <span class="badge badge-primary"><i class="far fa-edit mr-1"
                                                    aria-hidden="true"></i>Edit</span>
                                        </a>
                                        @endif

                                        <a href="{{route('universities.show',$university->id)}}">
                                            <span class="badge badge-primary"><i class="far fa-eye mr-1"
                                                    aria-hidden="true"></i>View</span>
                                        </a>

                                        @if (Auth::guard('admin')->user()->hasPermission('delete-universities'))
                                        <a href="{{route('universities.destroy',$university->id)}}">
                                            <span class="badge badge-danger"><i class="far fa-trash-alt mr-1"
                                                    aria-hidden="true"></i>Delete</span>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
      $("#universities_table").DataTable({
        "responsive": true,
        "autoWidth": false,
      });
    });
</script>
@endsection
