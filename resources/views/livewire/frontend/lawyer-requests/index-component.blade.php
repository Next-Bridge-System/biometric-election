<div>
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="text-capitalize">My Requests</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">

                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Total Certificates: {{$records->total()}}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="">
                                <table id="" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th style="width:3%">ID</th>
                                            <th>Lawyer Name</th>
                                            <th style="width:12%">CNIC</th>
                                            <th>License</th>
                                            <th style="width:8%">Amount</th>
                                            <th style="width:8%">Date</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th style="width:10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($records as $record)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$record->id}}</td>
                                            <td>{{$record->lawyer_name}} S/O {{$record->father_name}}</td>
                                            <td>{{$record->cnic_no}}</td>
                                            <td>{{$record->license_no}}</td>
                                            <td>Rs {{$record->amount}}/-</td>
                                            <td>{{getdateFormat($record->created_at)}}</td>
                                            <td>
                                                <span class="badge badge-primary">
                                                    {{$record->lawyer_request_sub_category->name}}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($record->approved == 1)
                                                <span class="badge badge-success">Approved</span>
                                                @elseif($record->approved == 2)
                                                <span class="badge badge-danger">Disapproved</span>
                                                @elseif($record->approved == 3)
                                                <span class="badge badge-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{route('frontend.lawyer-requests.show',$record->id)}}"
                                                    class="btn btn-primary btn-xs">
                                                    <i class="fas fa-list mr-1"></i>Detail</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-lg-6">
                                        Showing {{ $records->firstItem() }} to {{ $records->lastItem() }} of total
                                        {{ $records->total() }} entries
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="d-flex justify-content-end px-2 mx-2 my-2">
                                            {{ $records->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('livewire.loader')
</div>