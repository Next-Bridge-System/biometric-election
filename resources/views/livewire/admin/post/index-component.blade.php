<div>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="text-capitalize">Manage Posts - Submitted List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <div>
                            <button wire:click="create()" type="button" class="btn btn-success btn-sm float-right"
                                data-toggle="modal" data-target="#post_modal"><i class="fas fa-plus mr-1"></i>Add Post
                            </button>
                        </div>
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
                            <div class="filters row" style=" overflow-y: scroll;">
                                <div class="col-md-3 form-group">
                                    <label><strong>FILTERS</strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <select wire:model.defer="search_key"
                                                class="custom-select text-uppercase filter-options">
                                                <option value="name">Name</option>
                                                <option value="father">Father/Husband</option>
                                                <option value="cnic">CNIC NO</option>
                                            </select>
                                        </div>
                                        <input wire:model.defer="search_value" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label><strong>DATES</strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <select wire:model="search_date_type"
                                                class="custom-select text-uppercase filter-options">
                                                <option value="created_at">Created Date</option>
                                                <option value="rcpt_date">RCPT Date</option>
                                            </select>
                                        </div>
                                        <div class="input-group-prepend">
                                            <select wire:model="search_date_key"
                                                class="custom-select text-uppercase filter-options">
                                                <option value="">All</option>
                                                <hr>
                                                <option value="today">Today</option>
                                                <option value="yesterday">Yesterday</option>
                                                <option value="this_week">This Week</option>
                                                <option value="last_week">Last Week</option>
                                                <option value="this_month">This Month</option>
                                                <option value="last_month">Last Month</option>
                                                <option value="this_year">This Year</option>
                                                <option value="last_year">Last Year</option>
                                                <hr>
                                                <option value="date_range">Date Range</option>
                                            </select>
                                        </div>
                                        @if ($search_date_key =='date_range')
                                        <input type="date" wire:model.defer="search_date_value_1" class="form-control">
                                        <input type="date" wire:model.defer="search_date_value_2" class="form-control">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-sm btn-success" wire:click="search"><i
                                            class="fa fa-search mr-1"></i>Search</button>
                                    <button class="btn btn-sm btn-danger" wire:click="clear" {{!$search ? 'disabled'
                                        : '' }}><i class="fa fa-refresh mr-1"></i>Reset</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="" class="table table-bordered table-sm text-uppercase"
                                    style="font-size: 14px; white-space: nowrap;">
                                    <thead class="bg-success text-white">
                                        <tr>
                                            <th>Sr #</th>
                                            <th>Post ID</th>
                                            <th>Subject</th>
                                            <th>Post Type</th>
                                            <th>Name</th>
                                            <th>Father/Husband</th>
                                            <th>CNIC No</th>
                                            <th>Mobile No</th>
                                            <th>Station/Address</th>
                                            <th>Picture</th>
                                            <th>RCPT No & Date</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($records as $record)
                                        <tr class="{{$record->lawyer_type == $record->current_user_type ? '': ''}}">
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$record->id}}</td>
                                            <td>{{$record->subject}}</td>
                                            <td><span class="badge badge-primary">{{$record->postTypeRelation->name ??
                                                    ""}}</span></td>
                                            <td>{{$record->lawyer_name}}</td>
                                            <td>{{$record->father_husband}}</td>
                                            <td>{{$record->cnic_no}}</td>
                                            <td>{{$record->mobile_no}}</td>
                                            <td>{{$record->address}}</td>
                                            <td>
                                                @if ($record->webcam_image_url)
                                                <img src="{{asset('storage/app/public/'.$record->webcam_image_url)}}"
                                                    style="height: 30px; width:30px" alt="">
                                                @endif
                                            </td>
                                            <td>
                                                @if ($record->rcpt_no)
                                                <span> {{getDateFormat($record->rcpt_date)}}</span>
                                                <span class="badge badge-success float-right"
                                                    style="font-size:12px">{{$record->rcpt_no}}</span>
                                                @else
                                                <a href="#" data-toggle="modal" data-bs-toggle="tooltip"
                                                    data-target="#rcpt_modal"
                                                    wire:click="openRcpt('{{ $record->id }}')">
                                                    <span class="badge badge-primary"><i
                                                            class="fas fa-plus mr-1"></i>RCPT NO</span>
                                                </a>
                                                @endif
                                            </td>
                                            <td>{{getDateFormat($record->created_at)}}</td>
                                            <td>
                                                <button class="btn btn-primary btn-xs" href="#" data-toggle="modal"
                                                    data-bs-toggle="tooltip" data-target="#notes_modal"
                                                    wire:click="openNotes('{{ $record->id }}')">
                                                    @if ($record->postNotes->count() > 0)
                                                    <i class="far fa-copy mr-1"></i> Notes <span
                                                        class="badge badge-warning ml-1">{{$record->postNotes->count()}}</span>
                                                    @else
                                                    <i class="fas fa-plus mr-1"></i> Notes
                                                    @endif
                                                </button>

                                                <button wire:click="edit({{ $record->id }})"
                                                    class="btn btn-primary btn-xs" data-bs-toggle="modal"
                                                    data-bs-target="#post_modal">
                                                    <i class="fas fa-edit mr-1"></i>Edit</button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No Record Found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                @if ($records)
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
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('livewire.admin.post.update')
    @include('livewire.admin.post.rcpt')
    @include('livewire.admin.post.notes')

    @include('livewire.loader')
</div>

<style>
    .filters .form-control {
        font-size: 12px;
    }

    .filters label {
        font-size: 12px;
    }

    .filters .filter-options {
        border-radius: 2px 0px 0px 2px;
    }

    .filters select {
        font-size: 12px;
    }
</style>