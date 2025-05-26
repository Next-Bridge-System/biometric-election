@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Operators/Managers</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        @if (Auth::guard('admin')->user()->hasPermission('add-operators'))
                        <a href="{{route('admins.create')}}" class="btn btn-success mt-2">
                            <i class="fas fa-plus mr-1" aria-hidden="true"></i> Add Operator
                        </a>
                        @endif
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
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Operators List (Total Operators : {{$adminUsers->count()}})
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="admin_users" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Sr.#</th>
                                    <th>Operator Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    @if (Auth::guard('admin')->user()->hasPermission('edit-operators'))
                                    <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php $count=1; @endphp
                                @foreach ($adminUsers as $user)
                                <tr>
                                    <td>{{$count++}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>+92{{$user->phone}}</td>
                                    <td>
                                        @if ($user->status == 1)
                                        <span class="badge badge-success">Active</span>
                                        @else
                                        <span class="badge badge-danger">Suspended</span>
                                        @endif
                                    </td>
                                    @if (Auth::guard('admin')->user()->hasPermission('edit-operators'))
                                    <td class="text-center">
                                        <a href="{{route('admins.edit',$user->id)}}">
                                            <span class="badge badge-primary"><i class="far fa-edit mr-1"
                                                    aria-hidden="true"></i>Edit</span>
                                        </a>
                                        <a href="{{route('admins.audit',$user->id)}}">
                                            <span class="badge badge-primary"><i class="fas fa-list mr-1"
                                                    aria-hidden="true"></i>Audit</span>
                                        </a>
                                    </td>
                                    @endif
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
      $("#admin_users").DataTable({
        "responsive": true,
        "autoWidth": false,
      });
    });
</script>
@endsection
