<div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="text-bold">High Court - List</h5>
                        </div>
                        <div class="card-body">
                            <div class="filters p-2 border bg-light">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label><strong>FILTERS</strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <select wire:model="search_by" wire:change="changeFilter"
                                                    class="custom-select text-uppercase filter-options">
                                                    <option value="name">Lawyer Name</option>
                                                    <option value="cnic">Cnic No</option>
                                                    <option value="dob">DOB</option>
                                                    <option value="lic_no">License No</option>
                                                    <option value="hcr_no">HCR No</option>
                                                    <option value="bf_no">BF No</option>
                                                    <option value="enr_date">ENR Date</option>
                                                    <option value="rcpt_no">RCPT No</option>
                                                </select>
                                            </div>
                                            <input wire:model="search_data" type="{{$search_data_type}}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>...</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <select wire:model="search_by_02"
                                                    class="custom-select text-uppercase filter-options">
                                                    <option value="father_name">Father name</option>
                                                </select>
                                            </div>
                                            <input wire:model="search_data_02" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label><strong>STATION</strong></label>
                                        <select wire:model="search_voter_member" class="form-control custom-select">
                                            <option value="" selected>--Select Voter Member--</option>
                                            @foreach ($bars as $bar)
                                            <option value="{{$bar->id}}">{{$bar->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <table id=""
                                    class="table table-bordered table-striped table-sm text-uppercase text-center">
                                    <thead>
                                        <tr>
                                            <th>App.No</th>
                                            <th>Lawyer Name</th>
                                            <th>Father/Husband</th>
                                            <th>Cnic.No</th>
                                            <th>Enr.Date LC</th>
                                            <th>Enr.Date HC</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($records as $record)
                                        <tr class="{{$open_row_id == $record->id ? 'bg-secondary':''}}">
                                            <td>
                                                <a href="#" data-toggle="modal" data-bs-toggle="tooltip"
                                                    data-target="#record_detail"
                                                    wire:click="detail('{{ $record->id }}', '{{ $record->type }}')">

                                                    @if ($open_row_id == $record->id)
                                                    <span class=" badge badge-danger">
                                                        <i class="fas fa-minus" aria-hidden="true"></i>
                                                    </span>
                                                    @else
                                                    <span class=" badge badge-primary">
                                                        <i class="fas fa-plus" aria-hidden="true"></i>
                                                    </span>
                                                    @endif
                                                </a>
                                                {{$record->type}} {{$record->id}}
                                            </td>
                                            <td>{{$record->name}}</td>
                                            <td>{{$record->father}}</td>
                                            <td style="width:150px">{{$record->cnic}}</td>
                                            <td style="width:150px">{{$record->enr_date_lc}}</td>
                                            <td style="width:150px">{{$record->enr_date_hc}}</td>
                                            <td>
                                                {!!appStatus($record->app_status,$record->app_type)!!}

                                                @if ($record->hc_exemption)
                                                <span class="badge badge-warning">
                                                    {{$record->hc_exemption}}
                                                 </span>
                                                @endif
                                            </td>
                                            <td>
                                                <a target="_blank"
                                                    href="{{ route('high-court.prints.short-detail', ['id' => $record->id, 'type' => $record->type]) }}">
                                                    <span class="badge badge-primary"><i class="fas fa-print mr-1"
                                                            aria-hidden="true"></i>Report</span>
                                                </a>

                                                @if ($record->type == 'hc')
                                                <span>
                                                    <a href="{{route('high-court.show', $record->id)}}">
                                                        <span class="badge badge-primary">
                                                            <i class="fas fa-list mr-1"></i>Detail
                                                        </span>
                                                    </a>

                                                    {{-- @if (permission('edit-high-court'))
                                                    <a href="{{route('high-court.create-step-1', $record->id)}}">
                                                        <span class="badge badge-primary">
                                                            <i class="fas fa-edit mr-1"
                                                                aria-hidden="true"></i>Edit</span></a>
                                                    @endif --}}

                                                    @if ($record->rcpt_no)
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check mr-1"></i>RCPT</span>
                                                    @else
                                                    <a href="#" data-toggle="modal" data-bs-toggle="tooltip"
                                                        data-target="#rcpt_modal"
                                                        wire:click="openRcpt('{{ $record->id }}')">
                                                        <span class="badge badge-primary"><i
                                                                class="fas fa-plus mr-1"></i>RCPT
                                                            NO</span>
                                                    </a>
                                                    @endif
                                                </span>
                                                @endif

                                                @if ($record->type == 'gc_hc')
                                                <span>
                                                    <a href="{{route('gc-high-court.show', $record->id)}}">
                                                        <span class="badge badge-primary"><i
                                                                class="fas fa-list mr-1"></i>Detail
                                                        </span>
                                                    </a>

                                                    @if (permission('gc_high_court_edit'))
                                                    <a href="{{route('gc-high-court.edit', $record->id)}}"><span
                                                            class="badge badge-primary">
                                                            <i class="fas fa-edit mr-1"
                                                                aria-hidden="true"></i>Edit</span></a>
                                                    @endif
                                                </span>
                                                @endif


                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan=" 7" class="text-center">No Record Found.
                                            </td>
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

    <div wire:ignore.self class="modal fade" id="record_detail" tabindex="-1" role="dialog"
        aria-labelledby="roleFormModalTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Overview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div wire:loading style="min-height:100px">
                                Processing...Please wait.
                            </div>

                            <div wire:loading.remove>
                                <table class="table table-sm text-uppercase table-bordered text-center">
                                    <tr>
                                        <th>App No:</th>
                                        <td>{{$short_detail['app_no'] ?? '-'}}</td>
                                        <th>User Id:</th>
                                        <td>{{$short_detail['user_id'] ?? '-'}}</td>
                                        <th rowspan="4" class="text-center">
                                            <img src="{{$short_detail['img_url'] ?? ''}}" class="custom-image-preview"
                                                alt="">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Rcpt No:</th>
                                        <td>{!!$short_detail['rcpt_no'] ?? '-'!!}</td>
                                        <th>Voter Member:</th>
                                        <td>{!!$short_detail['voter_member'] ?? '-'!!}</td>
                                    </tr>
                                    <tr>
                                        <th>HCR No:</th>
                                        <td>{{$short_detail['hcr_no'] ?? '-'}}</td>
                                        <th>License No:</th>
                                        <td>{{$short_detail['lic_no'] ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <th>BF No:</th>
                                        <td>{{$short_detail['bf_no'] ?? '-'}}</td>
                                        <th>Enr Date:</th>
                                        <td>{{$short_detail['enr_date'] ?? '-'}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="rcpt_modal" tabindex="-1" role="dialog"
        aria-labelledby="roleFormModalTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">HIGH COURT - RCPT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div wire:loading style="min-height:100px">
                                Processing...Please wait.
                            </div>

                            <div wire:loading.remove class="text-center">

                                <h1>
                                    @if ($rcpt_no)
                                    <i class="fas fa-check text-success"></i>
                                    @else
                                    <i class="fas fa-exclamation-circle text-warning"></i>
                                    @endif
                                </h1>
                                <h5><b>High Court Application: {{$open_row_id}}</b></h5>

                                @if ($rcpt_no)
                                <h1>{{$rcpt_no}}</h1>
                                <h3>{{$rcpt_date}}</h3>
                                @else
                                <h5 class="text-danger">Note: Please confirm before proceeding
                                    because this action can't be revertable!</h5>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @if ($rcpt_no)
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        wire:click="close">Close</button>
                    @else
                    <button type="button" class="btn btn-danger" data-dismiss="modal" wire:click="close">Cancel</button>
                    <button type="button" class="btn btn-success" wire:click="updateRcpt('{{$open_row_id}}')">
                        Yes, Confirm it!</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('livewire.loader')
</div>

<style>
    .filters .filter-options {
        border-radius: 2px 0px 0px 2px;
    }

    .filters .form-control {
        font-size: 12px;
    }

    .filters select {
        font-size: 12px;
    }

    .filters label {
        font-size: 12px;
    }
</style>