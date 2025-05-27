@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Elections</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            @if (Auth::guard('admin')->user()->hasPermission('add-seats'))
                                <a href="{{ route('seats.create') }}" class="btn btn-success mt-2">
                                    <i class="fas fa-plus mr-1" aria-hidden="true"></i> Add Seat
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
                                Seats List (Total Seats : {{ $seats->count() }})
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="seats" class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Sr.#</th>
                                        <th>Election</th>
                                        <th>Name English</th>
                                        <th>Name Urdu</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        @if (Auth::guard('admin')->user()->hasPermission('edit-seats'))
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $count = 1; @endphp
                                    @foreach ($seats as $seat)
                                        <tr>
                                            <td>{{ $count++ }}</td>
                                            <td>{{ $seat->election->title_english }}</td>
                                            <td>{{ $seat->name_english }}</td>
                                            <td>{{ $seat->name_urdu }}</td>
                                            <td>
                                                @if ($seat->image_url)
                                                    <img src="{{ asset('storage/app/public/' . $seat->image_url) }}"
                                                        alt="" height="50" width="50">
                                                @endif
                                            </td>
                                            <td>
                                                @if ($seat->status == 1)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                                </img>
                                            <td>{{ $seat->createdBy->name }}</td>
                                            @if (Auth::guard('admin')->user()->hasPermission('edit-seats'))
                                                <td class="text-center">
                                                    <a href="{{ route('seats.edit', $seat->id) }}">
                                                        <span class="badge badge-primary"><i class="far fa-edit mr-1"
                                                                aria-hidden="true"></i>Edit</span>
                                                    </a>
                                                    {{-- <a href="{{ route('seats.destroy', $seat->id) }}">
                                                        <span class="badge badge-danger"><i class="fas fa-trash mr-1"
                                                                aria-hidden="true"></i>Delete</span>
                                                    </a> --}}
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
        $(function() {
            $("#seats").DataTable({
                "responsive": true,
                "autoWidth": false,
            });
        });
    </script>
@endsection
