@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Districts</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        @if (Auth::guard('admin')->user()->hasPermission('add-districts'))
                        <a href="{{route('districts.create')}}" class="btn btn-success">
                            <i class="fas fa-plus mr-1" aria-hidden="true"></i> Add District
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
                            Districts List (Total districts : {{$districts->count()}})
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="districts_table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width:20px">#</th>
                                    <th>Name</th>
                                    <th>Tehsils</th>
                                    @if (Auth::guard('admin')->user()->hasPermission('edit-districts') ||
                                    Auth::guard('admin')->user()->hasPermission('delete-districts'))
                                    <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($districts as $district)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$district->name}} - {{$district->code}}</td>
                                    <td>
                                        @foreach ($district->tehsils as $tehsil)
                                        <span class="badge badge-secondary">{{$tehsil->name}} - {{$tehsil->code}}</span>
                                        @endforeach
                                    </td>
                                    @if (Auth::guard('admin')->user()->hasPermission('edit-districts') ||
                                    Auth::guard('admin')->user()->hasPermission('delete-districts'))
                                    <td>
                                        @if (Auth::guard('admin')->user()->hasPermission('edit-districts'))
                                        <a href="{{route('districts.edit',$district->id)}}">
                                            <span class="badge badge-primary"><i class="far fa-edit mr-1"
                                                    aria-hidden="true"></i>Edit</span>
                                        </a>
                                        @endif

                                        @if (Auth::guard('admin')->user()->hasPermission('delete-districts'))
                                        <a href="javascript:void(0)" data-action="{{route('districts.destroy',$district->id)}}" onclick="deleteDistrict(this)">
                                            <span class="badge badge-danger">
                                                <i class="far fa-trash-alt mr-1" aria-hidden="true"></i>Delete</span>
                                        </a>
                                        @endif
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
      $("#districts_table").DataTable({
        "responsive": true,
        "autoWidth": false,
      });
    });

    function deleteDistrict(event){
        console.log(event.dataset.action);
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            console.log(result)
            if (result.value) {
                $(".custom-loader").removeClass('hidden');
                $.get(event.dataset.action, function (data, status) {
                    console.log(data);
                    location.reload();
                });
            }
        })
    }
</script>
@endsection
