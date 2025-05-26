<div>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="text-capitalize">GC Users - Data Verification</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">

                    </ol>
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
                                Total Requests: {{$records->total()}}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="bg-light p-4 border">
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <input wire:model="search_user_id" type="search" class="form-control"
                                            placeholder="User ID">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <input wire:model="search_name" type="search" class="form-control"
                                            placeholder="Lawyer Name">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <select wire:model="search_gc_status" class="form-control custom-select">
                                            <option value="" selected>Select Status</option>
                                            <option value="pending">Pending</option>
                                            <option value="approved">Approved</option>
                                            <option value="disapproved">Disapproved</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <table id="" class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>Sr.No.</th>
                                            <th>User ID</th>
                                            <th>Name</th>
                                            <th>CNIC</th>
                                            <th>Status</th>
                                            <th>Request Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($records as $record)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$record->id}}</td>
                                            <td>{{$record->name}}</td>
                                            <td>{{$record->cnic_no}}</td>
                                            <td>
                                                <span
                                                    class="badge badge-primary text-uppercase">{{$record->register_as}}</span>
                                                <span
                                                    class="badge badge-{{user_gc_status($record->gc_status)['badge']}} text-uppercase">{{user_gc_status($record->gc_status)['name']}}</span>
                                            </td>
                                            <td>{{getdateFormat($record->created_at)}}</td>
                                            <td>
                                                <a href="{{route('gc-users.show', $record->id)}}"
                                                    class="btn btn-primary btn-xs mr-1">
                                                    <i class="fas fa-list mr-1"></i>Detail</a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No Record Found.</td>
                                        </tr>
                                        @endforelse
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