@extends('layouts.admin')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Policies</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            @if (Auth::guard('admin')->user()->hasPermission('add-policies'))
                                <a href="{{route('policies.create')}}" class="btn btn-success">
                                    <i class="fas fa-plus mr-1" aria-hidden="true"></i> Add Policy
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
                                Policies List (Total Policies : {{$policies->count()}})
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="admin_users" class="table table-bordered table-striped table-sm">
                                <thead>
                                <tr>
                                    <th>Sr.#</th>
                                    <th>Title</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    @if (Auth::guard('admin')->user()->hasPermission('edit-policies'))
                                        <th>Action</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @php $count=1; @endphp
                                @foreach ($policies as $policy)
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{$policy->title}}</td>
                                        <td>{{date('d-m-Y',strtotime($policy->start_date))}}</td>
                                        <td>{{date('d-m-Y',strtotime($policy->end_date))}}</td>
                                        <td>
                                            <img style="max-height:100px;width:auto" src="{{ asset('storage/app/public/'.$policy->policy_url) }}" alt="">
                                        </td>
                                        <td>
                                            @if ($policy->status == 1)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">In-active</span>
                                            @endif
                                        </td>
                                        @if (Auth::guard('admin')->user()->hasPermission('edit-policies'))
                                            <td class="text-center">
                                                <a href="{{route('policies.edit',$policy->id)}}">
                                            <span class="badge badge-primary"><i class="far fa-edit mr-1"
                                                                                 aria-hidden="true"></i>Edit</span>
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
