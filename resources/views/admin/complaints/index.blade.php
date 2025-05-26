@extends('layouts.admin')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Inquiries</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Inquiries List (Total Inquiries : {{$complaints->count()}})
                        </h3>
                    </div>
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Type</th>
                                    <th>Message</th>
                                    <th>Image</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($complaints as $complaint)
                                <tr>
                                    <td>{{$complaint->id}}</td>
                                    <td>{{$complaint->name}}</td>
                                    <td>{{$complaint->email}}</td>
                                    <td>+92{{$complaint->phone}}</td>
                                    <td>{{$complaint->type}}</td>
                                    <td>{{$complaint->message}}</td>
                                    <td>
                                        {{$complaint->getFirstMedia()}}
                                        <img src="{{$complaint->getFirstMediaUrl()}}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(function () {
      $("#datatable").DataTable({
        "responsive": true,
        "autoWidth": false,
      });
    });
</script>
@endsection