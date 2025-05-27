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
                            @if (Auth::guard('admin')->user()->hasPermission('add-operators'))
                                <a href="{{ route('elections.create') }}" class="btn btn-success mt-2">
                                    <i class="fas fa-plus mr-1" aria-hidden="true"></i> Add Election
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
                                Elections List (Total Elections : {{ $elections->count() }})
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="elections" class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Sr.#</th>
                                        <th>Title English</th>
                                        <th>Title Urdu</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        @if (Auth::guard('admin')->user()->hasPermission('edit-elections'))
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $count = 1; @endphp
                                    @foreach ($elections as $election)
                                        <tr>
                                            <td>{{ $count++ }}</td>
                                            <td>{{ $election->title_english }}</td>
                                            <td>{{ $election->title_urdu }}</td>
                                            <td>
                                                @if ($election->status == 1)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Suspended</span>
                                                @endif
                                            </td>
                                            <td>{{ $election->createdBy->name }}</td>
                                            @if (Auth::guard('admin')->user()->hasPermission('edit-elections'))
                                                <td class="text-center">
                                                    <a href="{{ route('elections.edit', $election->id) }}">
                                                        <span class="badge badge-primary"><i class="far fa-edit mr-1"
                                                                aria-hidden="true"></i>Edit</span>
                                                    </a>
                                                    <a href="{{ route('elections.destroy', $election->id) }}">
                                                        <span class="badge badge-danger"><i class="fas fa-trash mr-1"
                                                                aria-hidden="true"></i>Delete</span>
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
        $(function() {
            $("#elections").DataTable({
                "responsive": true,
                "autoWidth": false,
            });
        });
    </script>
@endsection
