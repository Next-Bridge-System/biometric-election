<div>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="text-capitalize">Lawyer Requests</h1>
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
                                Total Certificates: {{$records->total()}}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-1 form-group">
                                    <label for="">Request ID</label>
                                    <input wire:model="search_id" type="search" class="form-control" placeholder="ID">
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="">Lawyer Name</label>
                                    <input wire:model="search_lawyer_name" type="search" class="form-control"
                                        placeholder="Name">
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="">Lawyer CNIC</label>
                                    <input wire:model="search_lawyer_cnic" type="search" class="form-control"
                                        placeholder="CNIC">
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="">Category</label>
                                    <select wire:model="search_sub_category_id" class="form-control custom-select">
                                        <option value="">All Categories</option>
                                        @foreach ($sub_categories as $sub_category)
                                        <option value="{{$sub_category->id}}">{{$sub_category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="">Status</label>
                                    <select wire:model="search_approved" class="form-control custom-select">
                                        <option value="">Select</option>
                                        <option value="1">Approved</option>
                                        <option value="2">Disapproved</option>
                                        <option value="3">Pending</option>
                                    </select>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="">Payment Status</label>
                                    <select wire:model="search_payment_status" class="form-control custom-select">
                                        <option value="">Select</option>
                                        <option value="paid">Paid</option>
                                        <option value="unpaid">Unpaid</option>
                                    </select>
                                </div>
                                <div class="col-md-1 form-group">
                                    <label for="">Voucher Status</label>
                                    <select wire:model="search_voucher_file" class="form-control custom-select">
                                        <option value="">Select</option>
                                        <option value="attached">Attached</option>
                                        <option value="pending">Not Attached</option>
                                    </select>
                                </div>
                            </div>
                            <div class="">
                                <table id="" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>ID</th>
                                            <th>Lawyer Name</th>
                                            <th>CNIC</th>
                                            <th>License</th>
                                            <th>Amount</th>
                                            <th>Request Date</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($records as $record)
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

                                                @if ($record->payment_status == 'paid')
                                                <span class="badge badge-success">Paid</span>
                                                @elseif($record->payment_status == 'unpaid')
                                                <span class="badge badge-danger">Unpaid</span>
                                                @endif

                                                @if ($record->voucher_file == 'attached')
                                                <span class="badge badge-success">
                                                    Voucher Attached
                                                </span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{route('lawyer-requests.show',$record->id)}}"
                                                    class="btn btn-primary btn-sm m-1">
                                                    <i class="fas fa-list mr-1"></i>Detail</a>

                                                @if (permission('lawyer_request_delete'))
                                                <button
                                                    onclick="confirmation('delete-lawyer-request','{{$record->id}}')"
                                                    class="btn btn-danger btn-sm m-1">
                                                    <i class="fa fa-trash mr-1"></i>Delete</button>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="10" class="text-center">No Record Found.</td>
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