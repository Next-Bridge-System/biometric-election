@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Candidates</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            @if (Auth::guard('admin')->user()->hasPermission('add-candidates'))
                                <a href="{{ route('candidates.create') }}" class="btn btn-success mt-2">
                                    <i class="fas fa-plus mr-1" aria-hidden="true"></i> Add Candidate
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
                                Candidates List (Total Candidates : {{ $candidates->count() }})
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="candidates" class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Sr.#</th>
                                        <th>Election</th>
                                        <th>Seat</th>
                                        <th>Name English</th>
                                        <th>Name Urdu</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        @if (Auth::guard('admin')->user()->hasPermission('edit-candidates'))
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $count = 1; @endphp
                                    @foreach ($candidates as $candidate)
                                        <tr>
                                            <td>{{ $count++ }}</td>
                                            <td>{{ $candidate->election->title_english }}</td>
                                            <td>{{ $candidate->seat->name_english }}</td>
                                            <td>{{ $candidate->name_english }}</td>
                                            <td>{{ $candidate->name_urdu }}</td>
                                            <td>
                                                @if ($candidate->image_url)
                                                    <img src="{{ asset('storage/app/public/' . $candidate->image_url) }}"
                                                        alt="" height="50" width="50">
                                                @endif
                                            </td>
                                            <td>
                                                @if ($candidate->status == 1)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $candidate->createdBy->name }}</td>
                                            @if (Auth::guard('admin')->user()->hasPermission('edit-candidates'))
                                                <td class="text-center">
                                                    <a href="{{ route('candidates.edit', $candidate->id) }}">
                                                        <span class="badge badge-primary"><i class="far fa-edit mr-1"
                                                                aria-hidden="true"></i>Edit</span>
                                                    </a>
                                                    <a href="{{ route('candidates.destroy', $candidate->id) }}">
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
            $("#candidates").DataTable({
                "responsive": true,
                "autoWidth": false,
            });
        });
    </script>
@endsection
