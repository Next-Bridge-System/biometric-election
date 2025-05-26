<div>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="text-capitalize">Lawyer Complaints</h1>
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
                                Total Complaints: {{$records->total()}}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 form-group">
                                    <label for="">Complaint ID</label>
                                    <input wire:model="filters.complaint_id" type="search" class="form-control"
                                        placeholder="ID">
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="">Complainant Name</label>
                                    <input wire:model="filters.complainant_name" type="search" class="form-control"
                                        placeholder="Name">
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="">Complainant CNIC</label>
                                    <input wire:model="filters.complainant_cnic" type="search" class="form-control"
                                        placeholder="CNIC">
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="">Status</label>
                                    <select wire:model="filters.complaint_status" class="form-control custom-select">
                                        <option value="">Select</option>
                                        @foreach ($complaint_statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="table-responsive" style="white-space: nowrap;">
                                <table id="" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr class="text-uppercase">
                                            <th>Sr.No.</th>
                                            <th>Complaint ID</th>
                                            <th>Complainant Name</th>
                                            <th>Father/Husband</th>
                                            <th>CNIC NO</th>
                                            <th>Complaint Date</th>
                                            <th>Status</th>
                                            <th>RCPT No & Date</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($records as $record)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$record->id}}</td>
                                            <td>{{$record->complainant_name}}</td>
                                            <td>{{$record->complainant_father}}</td>
                                            <td>{{$record->complainant_cnic}}</td>
                                            <td>{{getdateFormat($record->created_at)}}</td>
                                            <td>
                                                @if (isset($record->complaintStatus->name))
                                                <span class="badge badge-{{$record->complaintStatus->badge}} text-uppercase">{{$record->complaintStatus->name}}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($record->rcpt_no)
                                                <span> {{getDateFormat($record->rcpt_date)}}</span>
                                                <span class="badge badge-success float-right"
                                                    style="font-size:12px">{{$record->rcpt_no}}</span>
                                                @else
                                                <a href="#" data-toggle="modal" data-bs-toggle="tooltip"
                                                    data-target="#complaint_modal"
                                                    wire:click="openRcpt('{{ $record->id }}')">
                                                    <span class="badge badge-primary"><i
                                                            class="fas fa-plus mr-1"></i>RCPT NO</span>
                                                </a>
                                                @endif
                                            </td>
                                            <td>{{getDateFormat($record->created_at)}}</td>
                                            <td>
                                                <a href="{{route('complaint.show',$record->id)}}"
                                                    class="btn btn-primary btn-sm m-1">
                                                    <i class="fas fa-list mr-1"></i>Detail</a>
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

    @include('livewire.admin.complaint.rcpt')
    @include('livewire.loader')

</div>